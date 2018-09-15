<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/15 16:42
 * Email: 1183@mapgoo.net
 */

namespace app\api\model;


class ThemeModel
{
    protected $table = 'bas_theme';
    protected $pk = 'themeId';

    public function themeList()
    {
        $where['isDelete'] = 0;
        return $this->where($where)->field("themeId, taskColour, restColour")->order("sort DESC, updateTime DESC")->select();
    }
}