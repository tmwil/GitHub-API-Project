<?php

class GitData extends Database
{
    // Database parameters
    protected $table = 'git_repos';
    protected $keyfield = 'id';

    // General
    public $data;
    public $id;
    public $total_pages = 0;
    public $total_entries = 0;
    public $return_per_page; // Number of repositories to display in list view. Optionally passed on construct.

    // Git API settings
    private $git_user = ''; // Git username
    private $git_pass = ''; // Git password
    public $git_per_page = 100; // Number of repositories to retrieve from GitHub per page.
    public $git_sort = 'stars'; // What field are we sorting on?
    public $git_order = 'desc'; // Which direction are we sorting?
    public $git_language = 'php'; // What language do we want?

    public function __construct($per_page = 100)
    {
        parent::__construct();
        $this->return_per_page = $per_page;
        $this->total_entries = $this->count_records();
        $this->total_pages = ceil($this->total_entries / $this->return_per_page);
    }

    // Count records currently in DB
    public function count_records()
    {
        $sql = "SELECT COUNT(*) AS num FROM ".$this->table;
        $rows = $this->fetch($sql, array());
        $total_rows = $rows[0]['num'];

        return $total_rows;
    }

    // Load record from DB
    public function load($id)
    {
        $sql = "SELECT * FROM ".$this->table." WHERE ".$this->keyfield."=? LIMIT 1";
        $rows = $this->fetch($sql, array($id));
        if (!empty($rows)) {
            $this->data = $rows[0];
            $this->id = $rows[0][$this->keyfield];
        } else {
            $this->data = array();
            $this->id = 0;
        }
    }

    // Insert new record into DB using data in $fields array previously formatted by format_git_data function.
    public function insert($fields)
    {
        $keys = array_keys($fields);
        $params = array_values($fields);

        $sql = "INSERT INTO ".$this->table." SET ";
        $sql .= implode('=?, ', $keys)."=?";

        $stmt = $this->query($sql, $params);
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    // Update currently loaded record with data passed in $input array
    public function update($input)
    {
        $affected = 0;

        if (!empty($this->id) && !empty($input)) {
            $keys = array_keys($input);
            $params = array_values($input);

            $sql = "UPDATE ".$this->table." SET ";
            $sql .= implode('=?, ', $keys)."=?";
            $sql .= " WHERE ".$this->keyfield."=".$this->id;

            $stmt = $this->query($sql, $params);
            if ($stmt) {
                $affected = $stmt->rowCount();
            } else {
                $affected = false;
            }
        }

        return $affected;
    }

    // Get repositories from database.
    public function get_items($page_num = 1)
    {
        $sql = "SELECT * FROM ".$this->table." ORDER BY stars DESC LIMIT ?, ?";
        $params[] = ($page_num - 1) * $this->return_per_page;
        $params[] = $this->return_per_page;

        return $this->fetch($sql, $params);
    }

    // Format data into a nice array to insert into DB.
    protected function format_git_data($repo_data)
    {
        $foratted_data = array(
            'id' => $repo_data->id,
            'name' => $repo_data->name,
            'url' => $repo_data->html_url,
            'date_created' => date("Y-m-d H:i:s", strtotime($repo_data->created_at)),
            'date_last_push' => date("Y-m-d H:i:s", strtotime($repo_data->pushed_at)),
            'description' => (!empty($repo_data->description) ? $repo_data->description : ''),
            'stars' => $repo_data->stargazers_count,
        );

        return $foratted_data;
    }

    // Retrieve repositories from GitHub and store them in teh database.
    public function import_git_api_data()
    {
        $get_data = true;
        $git_page = 1;
        $git_num_pages = 0;
        $git_total = 0;
        $import_return_data = array(
            'git_items_received' => 0,
            'git_items_updated' => 0,
            'git_items_added' => 0,
        );
        // Lets keep getting repositories until we run out (or hit 10 pages of results).
        while ($get_data) {
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                "https://api.github.com/search/repositories?q=language:".$this->git_language."&sort=".$this->git_sort."&order=".$this->git_order."&per_page=".$this->git_per_page."&page=".$git_page
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERAGENT, $this->git_user);
            curl_setopt($ch, CURLOPT_USERPWD, $this->git_user.":".$this->git_pass);
            $data = json_decode(curl_exec($ch)) or die(curl_error($ch));
            curl_close($ch);
            if ($git_total == 0 && isset($data->total_count)) {
                $git_total = $data->total_count;
                $git_num_pages = ceil($git_total / $this->git_per_page);
            }
            if ($git_total > 0 && !empty($data->items)) {
//                echo 'PAGE: '.$git_page.' - '.date('Y-m-d H:i:s').'<br>';
                foreach ($data->items AS $k => $v) {
                    $import_return_data['git_items_received']++;
                    $insert_data = $this->format_git_data($v);
                    $this->load($v->id);

                    if (!empty($this->data)) {
                        if ($this->update($insert_data)) {
                            $import_return_data['git_items_updated']++;
                        }
                    } else {
                        if ($this->insert($insert_data)) {
                            $import_return_data['git_items_added']++;
                        }
                    }
                }

                // Limiting to a max of 10 pages here or 1000 repositories.
                if ($git_num_pages > $git_page && $git_page < 10) {
                    $git_page++;
                } else {
                    $get_data = false;
                }
            } else {
                $get_data = false;
            }
        }

        return $import_return_data;
    }

}