<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Builder;

/**
 * Interface BuildsFields.
 */
interface BuildsFields
{
    /**
     * @param string $path
     *
     * @return Field\Body
     */
    public function body(string $path): Field\Body;

    /**
     * @param string $name
     *
     * @return Field\Query
     */
    public function query(string $name): Field\Query;

    /**
     * @param string $name
     *
     * @return Field\File
     */
    public function file(string $name): Field\File;
}
