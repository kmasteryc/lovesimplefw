<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 07/08/2016
 * Time: 21:52
 */
namespace LoveSimple\Libs;
class Menu
{
    public $parent;
    public $html;

    public function displaySelectMenu($cates, $parent = 0, $divider = "___")
    {
        foreach ($cates as $cate) {
            if ($cate->cate_parent == $parent) {

                    $this->html .= "<option value='$cate->id'>";
                    $this->html .= $divider . $cate->cate_title;
                    $this->html .= "</option>";

                    $this->parent = $cate->id;
                    foreach ($cates as $cate_sub) {
                        if ($cate_sub->cate_parent == $this->parent) {

                            $divider .= $divider;
                            $this->displaySelectMenu($cates, $this->parent, $divider);
                            $divider = "___";

                        }
                    }
            }
        }
        return $this->html;
    }

    public function displaySelectedMenu($cates, $selected, $parent = 0, $divider = "___")
    {
        foreach ($cates as $cate) {
            if ($cate->cate_parent == $parent) {
                $select_parent = $selected->cate_parent;

                if ($cate->id != $selected->id) {
                    $this->html .= "<option value='$cate->id' " . ($cate->id == $select_parent ? 'selected' : '') . ">";
                    $this->html .= $divider . $cate->cate_title;
                    $this->html .= "</option>";

                    $this->parent = $cate->id;
                    foreach ($cates as $cate_sub) {
                        if ($cate_sub->cate_parent == $this->parent) {

                            $divider .= $divider;
                            self::displaySelectedMenu($cates, $selected, $this->parent, $divider);
                            $divider = "___";

                        }
                    }
                }
            }
        }
        return $this->html;
    }

    public function displayMenuInTable($cates, $parent = 0, $divider = "___")
    {
        foreach ($cates as $cate) {
            if ($cate->cate_parent == $parent) {
                $this->html .= "<tr>";
                $this->html .= "<td>";
                $this->html .= $cate->id;
                $this->html .= "</td>";
                $this->html .= "<td>";
                $this->html .= "<a href='/cate/$cate->id/edit'>Edit</a> 
                                        - <a href='/cate/$cate->id/delete'>Delete</a>";
                $this->html .= "<td>";
                $this->html .= $divider . $cate->cate_title;
                $this->html .= "</td>";
                $this->html .= "</tr>";

                $this->parent = $cate->id;
                foreach ($cates as $cate_sub) {
                    if ($cate_sub->cate_parent == $this->parent) {

                        $divider .= $divider;
                        self::displayMenuInTable($cates, $this->parent, $divider);
                        $divider = "___";
                    }

                }
            }
        }
        return $this->html;
    }

    public function displayNavMenu($cates, $parent = 0)
    {
        foreach ($cates as $cate) {
            if ($cate->cate_parent == $parent) {

                if (self::amISingleCate($cate->id, $cates) === true) {
                    $this->html .= "<li>";
                    $this->html .= "<a href='" . baseDir("chuyen-muc/$cate->cate_slug") . "'>$cate->cate_title</a>";
                } else {
                    $this->html .= "<li class='dropdown'>";
                    $this->html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">';
                    $this->html .= $cate->cate_title;
                    $this->html .= '</a>';
                }

                $this->parent = $cate->id;
                foreach ($cates as $cate_sub) {
                    if ($cate_sub->cate_parent == $this->parent) {
                        $this->html .= '<ul class="dropdown-menu" role="menu">';

                        self::displayNavMenu($cates, $this->parent);

                        $this->html .= '</ul>';
                    }
                }
                $this->html .= "</li>";
//                echo "</ul>";
            }
        }
        return $this->html;
    }

    public function amISingleCate($find, $cates)
    {
        $check = true;
        foreach ($cates as $cate) {
            if ($cate->cate_parent == $find) $check = false;
        }
        return $check;
    }
}