<?php

namespace AnourValar\EloquentSerialize\Grammars;

use AnourValar\EloquentSerialize\Package;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Concerns\SupportsInverseRelations;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

trait ModelGrammar
{
    /**
     * Pack
     */
    protected function pack(Builder $builder): Package
    {
        $this->setup();

        // Global scopes handle
        $builder = $builder->applyScopes();
        $builder->withoutGlobalScopes(array_keys($builder->getAllGlobalScopes()));

        return new Package([
            'model' => get_class($builder->getModel()),
            'connection' => $builder->getModel()->getConnectionName(),
            'eloquent' => $this->packEloquentBuilder($builder),
            'query' => $this->packQueryBuilder($builder->getQuery()),
        ]);
    }

    /**
     * Unpack
     */
    protected function unpack(Package $package): Builder
    {
        $this->setup();

        $builder = $package->get('model');
        $builder = $builder::on($package->get('connection'));

        $this->unpackEloquentBuilder($package->get('eloquent'), $builder);
        $this->unpackQueryBuilder($package->get('query'), $builder->getQuery());

        return $builder;
    }

    /**
     * init
     */
    private function setup(): void
    {
        $serializeMorphableEager = fn ($value) => $this->serializeMorphableEager($value);
        $unserializeMorphableEager = fn ($value) => $this->unserializeMorphableEager($value);

        Builder::macro('getAllGlobalScopes', function () {
            return $this->scopes;
        });

        Relation::macro('importExtraParametersForSerialize', function (array $params) use ($unserializeMorphableEager) {
            foreach ($params as $key => $value) {
                if ($key == 'morphableEagerLoads' || $key == 'morphableEagerLoadCounts') {
                    $value = $unserializeMorphableEager($value);
                }

                $this->$key = $value;
            }
        });

        Relation::macro('exportExtraParametersForSerialize', function () use ($serializeMorphableEager) {
            if ($this instanceof MorphTo) {
                return [
                    'morphableEagerLoads' => $serializeMorphableEager($this->morphableEagerLoads),
                    'morphableEagerLoadCounts' => $serializeMorphableEager($this->morphableEagerLoadCounts),
                    'morphableConstraints' => $this->morphableConstraints,
                ];
            }

            if (
                $this instanceof HasOneOrMany
                && in_array(SupportsInverseRelations::class, class_uses(HasOneOrMany::class)) // @TODO: >= 11.22
            ) {
                return [
                    'inverseRelationship' => $this->inverseRelationship,
                ];
            }

            return null;
        });
    }

    private function serializeMorphableEager(array $value): array
    {
        foreach ($value as $class => &$items) {
            foreach ($items as $relation => &$item) {
                if (! is_callable($item)) {
                    continue;
                }

                if (! method_exists($class, $relation)) {
                    throw new \RuntimeException("Serialization error. Does relation '{$relation}' exists in the model '{$class}' ?");
                }

                // ALT: $obj->cleanStaticConstraints($item['builder'], $obj->packQueryBuilder((new $class)->getQuery()->getQuery()));
                $eloquentBuilder = (new $class)->$relation()->getModel()->newQuery();
                $item($eloquentBuilder);

                $item = [
                    'eloquent' => $this->packEloquentBuilder($eloquentBuilder),
                    'builder' => $this->packQueryBuilder($eloquentBuilder->getQuery()),
                ];
            }
            unset($item);
        }
        unset($items);

        return $value;
    }

    private function unserializeMorphableEager(array $value): array
    {
        foreach ($value as &$items) {
            foreach ($items as &$item) {
                if (! is_array($item)) {
                    continue;
                }

                $item = function ($query) use ($item) {
                    if ($query instanceof Builder) {
                        $this->unpackEloquentBuilder($item['eloquent'], $query);
                        $this->unpackQueryBuilder($item['builder'], $query->getQuery());
                    } else {
                        $eloquent = $query->getQuery();
                        $this->unpackEloquentBuilder($item['eloquent'], $eloquent);
                        $this->unpackQueryBuilder($item['builder'], $query->getBaseQuery());
                    }
                };
            }
            unset($item);
        }
        unset($items);

        return $value;
    }
}
