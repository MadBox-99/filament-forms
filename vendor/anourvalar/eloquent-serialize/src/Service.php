<?php

namespace AnourValar\EloquentSerialize;

use AnourValar\EloquentSerialize\Grammars\EloquentBuilderGrammar;
use AnourValar\EloquentSerialize\Grammars\ModelGrammar;
use AnourValar\EloquentSerialize\Grammars\QueryBuilderGrammar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Relation;

class Service
{
    use EloquentBuilderGrammar;
    use ModelGrammar;
    use QueryBuilderGrammar;

    /**
     * Pack
     *
     * @throws \RuntimeException
     */
    public function serialize(Builder|Relation $builder): string
    {
        if (
            $builder instanceof HasOne
            || $builder instanceof HasMany
            || $builder instanceof BelongsTo // as well as MorphTo
            || $builder instanceof MorphOne
        ) {
            $builder = $builder->getQuery(); // chaperone/inverse is not supported!
        }

        if ($builder instanceof Relation) {
            throw new \RuntimeException(get_class($builder).' cannot be packed.');
        }

        $package = $this->pack($builder);

        return serialize($package); // important!
    }

    /**
     * Unpack
     *
     * @param  mixed  $package
     *
     * @throws \LogicException
     */
    public function unserialize($package): Builder
    {
        // Prepare data
        if (is_string($package)) {
            $package = unserialize($package);
        }
        if (! ($package instanceof Package)) {
            throw new \LogicException('Incorrect argument.');
        }

        // Unpack
        return $this->unpack($package);
    }
}
