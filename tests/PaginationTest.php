<?php
/**
 * fumseck added PaginationTest.php to imperium
 * The 24/03/18 at 17:05
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
 */


namespace tests {

    use Imperium\Html\Form\Form;
    use PHPUnit\Framework\TestCase;

    class PaginationTest extends TestCase
    {

        private $instance = 'imperium/admin';
        private  $current = 1;
        private  $total = 100;
        private  $start = 'previous';
        private  $end = 'next';
        private $class = 'pagination';
        private $per = '10';

        public function test()
        {
            $boot = pagination($this->per,$this->instance,$this->current,$this->total,$this->start,$this->end,$this->class,Form::BOOTSTRAP);
            $foundation = pagination($this->per,$this->instance,$this->current,$this->total,$this->start,$this->end,$this->class,Form::FOUNDATION);

            $this->assertContains('10',$boot);
            $this->assertContains('10',$foundation);

            $this->assertContains('1',$boot);
            $this->assertContains('1',$foundation);

            $this->assertContains('previous',$boot);
            $this->assertContains('previous',$foundation);

            $this->assertContains('next',$boot);
            $this->assertContains('next',$foundation);

            $this->assertContains('pagination',$boot);
            $this->assertContains('pagination',$foundation);

            $this->assertContains('imperium/admin',$boot);
            $this->assertContains('imperium/admin',$foundation);


        }
    }
}