<?php

/**
 * 数据库Postgre返回类
 *
 * @author     呼吸二氧化碳 <jonwang@myqee.com>
 * @category   Driver
 * @package    Database
 * @subpackage Postgre
 * @copyright  Copyright (c) 2008-2016 myqee.com
 * @license    http://www.myqee.com/license.html
 */
class Driver_Database_Driver_Postgre_Result extends Database_Result
{
    protected function release_resource()
    {
        if (is_resource($this->_result))
        {
            pg_free_result($this->_result);
        }
        $this->_result = null;
    }

    protected function total_count()
    {
        if ($this->_result)
        {
            $count = @pg_num_rows($this->_result);
            if (!$count>0)$count = 0;
        }
        else
        {
            $count = count($this->_data);
        }

        return $count;
    }

    public function seek($offset)
    {
        if (isset($this->_data[$offset]))
        {
            return true;
        }
        elseif ($this->offsetExists($offset) && $this->_result && pg_result_seek($this->_result, $offset))
        {
            $this->_current_row = $this->_internal_row = $offset;

            return true;
        }
        else
        {
            return false;
        }
    }

    protected function fetch_assoc()
    {
        return pg_fetch_assoc($this->_result);
    }
}