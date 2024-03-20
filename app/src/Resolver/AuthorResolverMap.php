<?php

namespace App\Resolver;

use App\Service\AuthorService;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

class AuthorResolverMap extends ResolverMap
{
    public function __construct(
        private readonly AuthorService $authorService,
    ) {
    }

    protected function map(): array
    {
        return [
            'AuthorQuery' => [
                self::RESOLVE_FIELD => function (
                    $value,
                    ArgumentInterface $args,
                    \ArrayObject $context,
                    ResolveInfo $info
                ) {
                    // temp for php stan
                    assert(is_string($args['id']));

                    return match ($info->fieldName) {
                        'author' => $this->authorService->getAuthor($args['id']),
                        'authors' => $this->authorService->getAuthors(),
                        default => null
                    };
                },
            ],
            'AuthorMutation' => [
                self::RESOLVE_FIELD => function (
                    $value,
                    ArgumentInterface $args,
                    \ArrayObject $context,
                    ResolveInfo $info
                ) {
                    // temp for php stan
                    assert(is_string($args['id']));
                    assert(is_array($args['author']));

                    return match ($info->fieldName) {
                        'createAuthor' => $this->authorService->createAuthor($args['author']),
                        'updateAuthor' => $this->authorService->updateAuthor($args['id'], $args['author']),
                        'deleteAuthor' => $this->authorService->deleteAuthor($args['id']),
                        default => null
                    };
                },
            ],
        ];
    }
}
