<?php

defined('_EXEC') or die;

class Uploader
{
    private $security;
    private $show_404;
    private $file_name;
    private $file_temporal_name;
    private $file_type;
    private $file_size;
    private $upload_directory;
    private $valid_extensions;
    private $maximum_file_size;

    public function __construct($show_404 = false)
    {
        $this->security = new Security();
        $this->show_404 = $show_404;
    }

    public function set_file_name()
    {
        $this->file_name = $this->security->random_string(16);
    }

    public function set_file_temporal_name($argv)
    {
        $this->file_temporal_name = $argv;
    }

    public function set_file_type($argv)
    {
        $this->file_type = explode('/', $argv);

        if ($this->file_type[1] == 'vnd.openxmlformats-officedocument.wordprocessingml.document')
            $this->file_type[1] = 'docx';
        else if ($this->file_type[1] == 'vnd.ms-excel')
            $this->file_type[1] = 'xls';
        else if ($this->file_type[1] == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            $this->file_type[1] = 'xlsx';
    }

    public function set_file_size($argv)
    {
        $this->file_size = $argv;
    }

    public function set_upload_directory($argv)
    {
        $this->upload_directory = $argv;
    }

    public function set_valid_extensions($argv)
    {
        $this->valid_extensions = $argv;
    }

    public function set_maximum_file_size($argv)
    {
        $this->maximum_file_size = $argv;
    }

    public function upload_file()
    {
        if (!empty($this->valid_extensions))
        {
            if (isset($this->file_type[1]))
            {
                if (in_array($this->file_type[1], $this->valid_extensions))
                {
                    if ($this->file_size < $this->maximum_file_size || $this->maximum_file_size == 'unlimited')
                    {
                        if (@copy($this->file_temporal_name, $this->upload_directory . '/' . $this->file_name . '.' . $this->file_type[1]))
                        {
                            return [
                                'status'    => 'success',
                                'file'      => $this->file_name . '.' . $this->file_type[1],
                                'route'     => $this->security->DS($this->upload_directory . '/' . $this->file_name . '.' . $this->file_type[1])
                            ];
                        }
                        else
                        {
                            if ($this->show_404 == true)
                                header("HTTP/1.0 404 Not Found");

                            return [
                                'status' => 'error',
                                'message' => '{system_failed}'
                            ];
                        }
                    }
                    else
                    {
                        if ($this->show_404 == true)
                            header("HTTP/1.0 404 Not Found");

                        return [
                            'status' => 'error',
                            'message' => '{file_is_larger}'
                        ];
                    }
                }
                else
                {
                    if ($this->show_404 == true)
                        header("HTTP/1.0 404 Not Found");

                    return [
                        'status' => 'error',
                        'message' => '{file_not_allowed}'
                    ];
                }
            }
            else
            {
                if ($this->show_404 == true)
                    header("HTTP/1.0 404 Not Found");

                return [
                    'status' => 'error',
                    'message' => '{file_error}'
                ];
            }
        }
        else
        {
            if ($this->show_404 == true)
                header("HTTP/1.0 404 Not Found");

            return [
                'status' => 'error',
                'message' => '{extension_not_valid}'
            ];
        }
    }
}
