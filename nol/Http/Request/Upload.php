<?php

/**
 * Copyright (C) <2020>  <Willy Micieli>
 *
 * This program is free software : you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https: //www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace Nol\Http\Request {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Nol\Http\Parameters\Bag;
    use Nol\Http\Response\Response;
    use Nol\Security\Validator\Validator;


    /**
     *
     * Represent all uploaded files.
     *
     * This package contains all methods useful to manage $_FILES contents.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Http\Parameters\UploadedFile
     * @version 12
     */
    abstract class Upload extends Validator
    {
        /**
         * @var array<array> All dimensions sizes
         */
        protected array $sizes = [
            'mobile' => [150, 150],
            'tablet' => [300, 300],
            'laptop' => [600, 600],
            'desktop' => [1024, 1024]
        ];

        /**
         *
         * Do something with the validated request.
         *
         * @param Bag $bag The validate
         *
         * @return Response
         *
         */
        abstract public function do(Bag $bag): Response;

        /**
         * @param Request $request
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Response
         *
         */
        public function apply(Request $request): Response
        {
            if ($this->check($request->request()) && $request->hasToken()) {
                return $this->do($request->request());
            }
            return $this->redirect();
        }
    }
}
