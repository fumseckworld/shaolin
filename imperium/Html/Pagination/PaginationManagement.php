<?php
/**
 * fumseck added PaginationManagement.php to imperium
 * The 06/11/17 at 11:53
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package : imperium
 * @author  : fumseck
 */


namespace Imperium\Html\Pagination;


interface PaginationManagement
{
	
	/**
	 *
	 * @param  int  $current_page
	 * @param  int  $limit
	 * @param  int  $total
	 */
	public function __construct(int $current_page,int $limit,int $total);
	
	/**
	 *
	 * Get the pagination
	 *
	 * @return string
	 *
	 */
	public function paginate(): string ;
	
}