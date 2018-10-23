<?php

namespace CloudCreativity\LaravelJsonApi\Queue;

use Carbon\Carbon;
use Neomerx\JsonApi\Schema\SchemaProvider;

class ClientJobSchema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'queue-jobs';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d\TH:i:s.uP';

    /**
     * @param ClientJob $resource
     * @return string
     */
    public function getId($resource)
    {
        return $resource->getRouteKey();
    }

    /**
     * @param ClientJob $resource
     * @return array
     */
    public function getAttributes($resource)
    {
        /** @var Carbon|null $completedAt */
        $completedAt = $resource->completed_at;
        /** @var Carbon|null $timeoutAt */
        $timeoutAt = $resource->timeout_at;

        return [
            'attempts' => $resource->attempts,
            'created-at' => $resource->created_at->format($this->dateFormat),
            'completed-at' => $completedAt ? $completedAt->format($this->dateFormat) : null,
            'failed' => $completedAt ? $resource->failed : null,
            'resource' => $resource->resource_type,
            'timeout' => $resource->timeout,
            'timeout-at' => $timeoutAt ? $timeoutAt->format($this->dateFormat) : null,
            'tries' => $resource->tries,
            'status' => $resource->status,
            'updated-at' => $resource->updated_at->format($this->dateFormat),
        ];
    }

    /**
     * @param ClientJob $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'target' => [
                self::SHOW_SELF => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA => isset($includeRelationships['target']),
                self::DATA => function () use ($resource) {
                    return $resource->getTarget();
                },
            ],
        ];
    }


}
