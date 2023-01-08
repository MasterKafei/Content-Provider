<?php

namespace App\Business;

use App\Business\Model\IgdbRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IgdbBusiness
{

    public function __construct(
        private readonly HttpClientInterface $igdb,
        private readonly TwitchBusiness      $twitchBusiness,
        private readonly RouterInterface     $router,
    )
    {
    }

    public function transformBody(IgdbRequest $request): string
    {
        $fields = implode(', ', $request->getFields());
        $conditions = $request->getConditions();
        foreach ($request->getFieldsNotNullable() as $fieldNotNullable) {
            $conditions[] = "$fieldNotNullable != null";
        }
        $wheres = implode(' & ', $conditions);

        $body = '';
        if (!empty($fields)) {
            $body .= "fields: $fields;";
        }

        if (!empty($wheres)) {
            $body .= "where $wheres;";
        }

        if ($request->getSearch()) {
            $body .= "search \"{$request->getSearch()}\";";
        }

        $body .= "limit: {$request->getLimit()};offset: {$request->getOffset()};";

        if (null !== $request->getFieldOrder()) {
            $body .= "sort {$request->getFieldOrder()} {$request->getSortOrder()};";
        }

        return $body;
    }

    public function request(IgdbRequest $request): array
    {
        $token = $this->twitchBusiness->getAccessToken();

        $body = $this->transformBody($request);
        return $this->igdb->request(Request::METHOD_POST, $this->router->generate($request->getRouteName(), $request->getRouteParameters()), [
            'body' => $body,
            'headers' => [
                'Authorization' => "Bearer $token",
            ],
        ])->toArray(false);
    }
}
