<?php

class pagination
{

    var $base_url = '';
    var $prefix   = '_';
    var $suffix   = '';
    var $total_rows           = 0;
    var $per_page             = 10;
    var $num_links            = 2;
    var $cur_page             = 0;
    var $use_page_numbers     = FALSE;
    var $first_link           = '&lsaquo; First';
    var $next_link            = '&gt;';
    var $prev_link            = '&lt;';
    var $last_link            = 'Last &rsaquo;';
    var $uri_segment          = 3;
    var $full_tag_open        = '';
    var $full_tag_close       = '';
    var $first_tag_open       = '';
    var $first_tag_close      = '&nbsp;';
    var $last_tag_open        = '&nbsp;';
    var $last_tag_close       = '';
    var $first_url            = '';
    var $cur_tag_open         = '&nbsp;<strong>';
    var $cur_tag_close        = '</strong>';
    var $next_tag_open        = '&nbsp;';
    var $next_tag_close       = '&nbsp;';
    var $prev_tag_open        = '&nbsp;';
    var $prev_tag_close       = '';
    var $num_tag_open         = '&nbsp;';
    var $num_tag_close        = '';
    var $page_query_string    = FALSE;
    var $query_string_segment = 'cur_page';
    var $display_pages        = TRUE;
    var $anchor_class         = '';
    var $need_page_info       = array();
    var $need_show_all_pages  = FALSE;
    var $all_link             = 'all_link';
    var $is_show_total        = 0;

    /**
     * Constructor
     *
     * @access	public
     * @param	array	initialization parameters
     */
    public function __construct($params = array(), $need_page_info = array())
    {
        if (count($params) > 0)
        {
            $this->initialize($params);
        }

        if ($this->anchor_class != '')
        {
            $this->anchor_class = 'class="' . $this->anchor_class . '" ';
        }

        if ($need_page_info)
        {
            $this->need_page_info = $need_page_info;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Initialize Preferences
     *
     * @access	public
     * @param	array	initialization parameters
     * @return	void
     */
    function initialize($params = array())
    {
        if (count($params) > 0)
        {
            foreach ($params as $key => $val)
            {
                if (isset($this->$key))
                {
                    $this->$key = $val;
                }
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Generate the pagination links
     *
     * @access	public
     * @return	string
     */
    function create_links()
    {
        if ($this->total_rows == 0 OR $this->per_page == 0)
        {
            return '';
        }

        $num_pages = ceil($this->total_rows / $this->per_page);

        $_REQUEST['__page_num_pages'] = $num_pages;

        if ($num_pages == 1)
        {
            return '';
        }

        if ($this->use_page_numbers)
        {
            $base_page = 1;
        }
        else
        {
            $base_page = 0;
        }

        $this->cur_page = (int) $this->cur_page;

        if ($this->use_page_numbers AND $this->cur_page == 0)
        {
            $this->cur_page = $base_page;
        }

        $this->num_links = (int) $this->num_links;

        if ($this->num_links < 1)
        {
            return ('NOT USE NUMBER.');
        }

        if (!is_numeric($this->cur_page))
        {
            $this->cur_page = $base_page;
        }

        if ($this->use_page_numbers)
        {
            if ($this->cur_page > $num_pages)
            {
                $this->cur_page = $num_pages;
            }
        }
        else
        {
            if ($this->cur_page > $this->total_rows)
            {
                $this->cur_page = ($num_pages - 1) * $this->per_page;
            }
        }

        $uri_page_number = $this->cur_page;

        if (!$this->use_page_numbers)
        {
            $this->cur_page = floor(($this->cur_page / $this->per_page) + 1);
        }

        $_REQUEST['__page_cur_page'] = $this->cur_page;

        $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
        $end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

        if ($this->need_page_info)
        {
            if($this->need_page_info['page_url'])
            {
                $this->base_url = $this->need_page_info['page_url'] . '/' . $this->need_page_info['page_filename'];
            }
            else
            {
                $this->base_url = $this->need_page_info['page_filename'];
            }
            if ($this->need_page_info['file_mktype'] == 1)
            {
                //$this->base_url .= '_';
            }
        }
        else
        {
            $this->base_url = rtrim($this->base_url);
            $this->base_url .= (!strpos($this->base_url, '?') ? '?' : '&amp;') . $this->query_string_segment . '=';
        }

        $output = '';

        if ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1))
        {
            $first_url = ($this->first_url == '') ? $this->base_url : $this->first_url;
            if ($this->need_page_info['file_mktype'] != 1)
            {
                $first_n = $first_url . '';
            }
            else
            {
                $first_n = $first_url . $this->suffix;
            }
            $output .= $this->first_tag_open . '<a ' . $this->anchor_class . 'href="' . $first_n . '">' . $this->first_link . '</a>' . $this->first_tag_close;
        }

        if ($this->prev_link !== FALSE AND $this->cur_page != 1)
        {
            if ($this->use_page_numbers)
            {
                $i = $uri_page_number - 1;
            }
            else
            {
                $i = $uri_page_number - $this->per_page;
            }

            if ($i == 0 && $this->first_url != '')
            {
                $output .= $this->prev_tag_open . '<a ' . $this->anchor_class . 'href="' . $this->first_url . '">' . $this->prev_link . '</a>' . $this->prev_tag_close;
            }
            else
            {
                $i = ($i == 0 || $this->need_page_info['file_mktype'] != 1) ? $i : (($i == 1 ? '' : ($this->prefix . $i)) . $this->suffix);
                $output .= $this->prev_tag_open . '<a ' . $this->anchor_class . 'href="' . $this->base_url . $i . '">' . $this->prev_link . '</a>' . $this->prev_tag_close;
            }
        }

        if ($this->display_pages !== FALSE)
        {
            for ($loop = $start - 1; $loop <= $end; $loop++)
            {
                if ($this->use_page_numbers)
                {
                    $i = $loop;
                }
                else
                {
                    $i = ($loop * $this->per_page) - $this->per_page;
                }

                if ($i >= $base_page)
                {
                    if ($this->cur_page == $loop)
                    {
                        $output .= $this->cur_tag_open . $loop . $this->cur_tag_close; // Current page
                    }
                    else
                    {
                        $n = ($i == $base_page) ? '' : $i;

                        if ($n == '' && $this->first_url != '')
                        {
                            $output .= $this->num_tag_open . '<a ' . $this->anchor_class . 'href="' . $this->first_url . '">' . $loop . '</a>' . $this->num_tag_close;
                        }
                        else
                        {
                            if ($this->need_page_info['file_mktype'] != 1)
                            {
                                $n = ($n == '') ? '' : $n;
                            }
                            else
                            {
                                $n = ($n == '') ? $this->suffix : $this->prefix . $n . $this->suffix;
                            }
                            $output .= $this->num_tag_open . '<a ' . $this->anchor_class . 'href="' . $this->base_url . $n . '">' . $loop . '</a>' . $this->num_tag_close;
                        }
                    }
                }
            }
        }

        if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
        {
            if ($this->use_page_numbers)
            {
                $i = $this->cur_page + 1;
            }
            else
            {
                $i = ($this->cur_page * $this->per_page);
            }
            if ($this->need_page_info['file_mktype'] != 1)
            {
                $next_n = $i;
            }
            else
            {
                $next_n = $this->prefix . $i . $this->suffix;
            }
            $output .= $this->next_tag_open . '<a ' . $this->anchor_class . 'href="' . $this->base_url . $next_n . '">' . $this->next_link . '</a>' . $this->next_tag_close;
        }

        if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages)
        {
            if ($this->use_page_numbers)
            {
                $i = $num_pages;
            }
            else
            {
                $i = (($num_pages * $this->per_page) - $this->per_page);
            }
            if ($this->need_page_info['file_mktype'] != 1)
            {
                $last_n = $i;
            }
            else
            {
                $last_n = $this->prefix . $i . $this->suffix;
            }
            $output .= $this->last_tag_open . '<a ' . $this->anchor_class . 'href="' . $this->base_url . $last_n . '">' . $this->last_link . '</a>' . $this->last_tag_close;
        }
        
        if ($this->next_link !== FALSE AND $this->cur_page < $num_pages AND $this->need_page_info['need_show_all_pages'])
        {
            if ($this->need_page_info['file_mktype'] != 1)
            {
                $all_n = $i;
            }
            else
            {
                $all_n = $this->prefix . 'all' . $this->suffix;
            }
            $output .= $this->next_tag_open . '<a ' . $this->anchor_class . 'href="' . $this->base_url .$all_n. '">' . $this->all_link . '</a>' . $this->next_tag_close;
        }
        
        $totalstr = $this->cur_tag_open.'共'.$num_pages.'页/计'.$this->total_rows.'条'.$this->cur_tag_close;

        $output = preg_replace("#([^:])//+#", "\\1/", $output);

        $output = $this->full_tag_open . $output ;
        if($this->is_show_total)
        {
            $output .= $totalstr ;
        }
        $output .= $this->full_tag_close;

        return $output;
    }

}
