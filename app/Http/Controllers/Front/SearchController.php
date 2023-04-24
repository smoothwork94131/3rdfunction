<?php 

namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class SearchController extends Controller{
    
    public function index(Request $request, $key = "", $keyword = "") {
        $search_word = $keyword ;
        $tbl_name = $key ;

        $small_words_array = array('a', 'the', 'an', 'kioti', 'need', 'i', 'want', 'get', 'as', 'for');

        $search_word_array = preg_split("/[\s,]+/", $search_word);

        $unset_keys_array = [];
        foreach($search_word_array as $key => $sh_wd) {
            $flag = 0;
            foreach($small_words_array as $word) {
                if($sh_wd == $word) {
                    $flag=1;
                    break;
                }   
            }

            if($flag == 1) {
                $unset_keys_array[] = $key;
            }
        }

        foreach($unset_keys_array as $item) {
            unset($search_word_array[$item]);
        }

        $search_word_array = array_values($search_word_array);

        $category = DB::table('categories')
                ->where('name', $tbl_name)
                ->get();

        $category_id = $category[0]->id;

        $series = DB::table('categories')
                ->where('parent', $category_id)
                ->where('status', 1)
                ->get();

        $model_array = array();

        foreach($series as $ser) {
            $model = DB::table(strtolower($ser->name).'_categories')
                    ->select('model as model_name')
                    ->orderBy('model', 'asc')
                    ->get()
                    ->groupBy('model_name');

            $model_temp = [];
            foreach($model as $key => $item) {
                $model_temp[] = $key;
            }

            $model_array[$ser->name] = $model_temp;
        }

        $search_model = array();
        foreach($model_array as $key=>$item) {
            $temp = [];
            foreach($item as $sub_item) {
                $flag = 0;
                
                foreach($search_word_array as $sh_key => $sh_word) {
                    if(strtolower($sh_word) === strtolower($sub_item)) {
                        $flag = 1;
                        $temp[] = $sub_item;
                        break;
                    }   
                }
                
                if($flag  == 1) {
                    unset($search_word_array[$sh_key]);
                    $search_word_array = array_values($search_word_array);
                }
            }

            if(count($temp) != 0) {
                $search_model[$key] = $temp;
            }
        }

        $like_clause = "";
        $like_match_clause = "";

        if(count($search_word_array) != 0) {
            $start_clause = " and ( ";
            $flag = 0;
            $search_sum = "";
            foreach($search_word_array as $like_item) {
                if($flag == 1) {
                    $like_clause .= ' or ';
                    $search_sum .= ' ';
                }
                $like_clause .= "`sku` like '%{$like_item}%' or `name` like '%{$like_item}%'" ;
                $search_sum .= $like_item;
                $flag = 1;
            }

            $like_clause = $start_clause . $like_clause;
            $like_clause .= ')';

            $replacements = [
                "oil filter" => "filter, oil",
                "gas filter" => "filter, gas"
            ];
            
            foreach ($replacements as $needle => $replacement) {
                if (strpos($search_sum, $needle) !== false) {
                    $search_sum = str_replace($needle, $replacement, $search_sum);
                }
            }

            $like_match_clause = $start_clause . "`sku` like '%{$search_sum}%' or `name` like '%{$search_sum}%' )";
        }

        $sql = "" ;
        $sql_match = "" ; 
        $flag = false ;

        foreach($search_model as $search_key => $search_item) {
            if($flag) {
                $sql.=" union all " ;
            }
            
            $sub_flag = false;
            $sub_sql = "( ";
            $sub_sql_match = "";

            foreach($search_item as $sub_key => $sub_item) {
                if($sub_flag) {
                    $sub_sql.=" or ";
                }

                $sub_sql .= "subcategory_id=UPPER('{$sub_item}') ";
                $sub_flag = true;
            }

            $sub_sql_match = $sub_sql . ')' . $like_match_clause;
            $sub_sql .= ')' . $like_clause;

            $table_name = strtolower($search_key);
            
            $sql .= "select `subcategory_id`, `category_id`, `name`, `photo`, `price`, `thumbnail`, `parent`, `sku`, `id`, `product_type`, '{$search_key}' as `table` from `{$table_name}` where {$sub_sql} " ;
            $sql_match .= "select `subcategory_id`, `category_id`, `name`, `photo`, `price`, `thumbnail`, `parent`, `sku`, `id`, `product_type`, '{$search_key}' as `table` from `{$table_name}` where {$sub_sql_match} " ;

            $flag = true ;
        }

        $categories = array();
        $categories_match = false;
        $result = array();
        if($sql != "") {
            $categories =DB::select($sql) ;
            if(count($search_word_array) > 1) {
                $categories_match = DB::select($sql_match);
            }

            if($categories_match) {
                foreach($categories as $key => $item) {
                    $flag = false;
                    foreach($categories_match as $sub_item) {
                        if($item->id == $sub_item->id) {
                            $flag = true;
                            break;
                        }
                    }

                    if($flag == true) {
                        unset($categories[$key]);
                        $categories = array_values($categories);
                    }
                }              

                $result = array_merge($categories_match, $categories);
            }
            else {
                $result = $categories;
            }

            $data = array();
            foreach($result as $key => $item) {
                $table_name = strtolower($item->table);
                $sql = "select * from `{$table_name}_categories` where `group_Id`='{$item->category_id}' and `model`='{$item->subcategory_id}'" ;
                $ret = DB::select($sql) ;
                if($ret) {
                    $item->group_name = $ret[0]->group_name ;
                    $item->section = $ret[0]->section_name ;
                    $data[$key] = $item ;
                }
            }
            $result = $data ;
        }

        $products = $result ;
        return view('front.search', compact('products', 'tbl_name'));
    }
    
    public function search(Request $request) {
        $search_word = $_REQUEST['search_word'] ;
        $tbl_name = $_REQUEST['key'] ;
        
        $small_words_array = array('a', 'the', 'an', 'kioti', 'need', 'i', 'want', 'get', 'as', 'for');
        $search_word_array = preg_split("/[\s,]+/", $search_word);

        $unset_keys_array = [];
        foreach($search_word_array as $key => $sh_wd) {
            $flag = 0;
            foreach($small_words_array as $word) {
                if($sh_wd == $word) {
                    $flag=1;
                    break;
                }   
            }

            if($flag == 1) {
                $unset_keys_array[] = $key;
            }
        }

        foreach($unset_keys_array as $item) {
            unset($search_word_array[$item]);
        }

        $search_word_array = array_values($search_word_array);

        $category = DB::table('categories')
                ->where('name', $tbl_name)
                ->get();

        $category_id = $category[0]->id;

        $series = DB::table('categories')
                ->where('parent', $category_id)
                ->where('status', 1)
                ->get();

        $model_array = array();

        foreach($series as $ser) {
            $model = DB::table(strtolower($ser->name).'_categories')
                    ->select('model as model_name')
                    ->orderBy('model', 'asc')
                    ->get()
                    ->groupBy('model_name');

            $model_temp = [];
            foreach($model as $key => $item) {
                $model_temp[] = $key;
            }

            $model_array[$ser->name] = $model_temp;
        }

        $search_model = array();
        foreach($model_array as $key=>$item) {
            $temp = [];
            foreach($item as $sub_item) {
                $flag = 0;
                
                foreach($search_word_array as $sh_key => $sh_word) {
                    if(strtolower($sh_word) === strtolower($sub_item)) {
                        $flag = 1;
                        $temp[] = $sub_item;
                        break;
                    }   
                }
                
                if($flag  == 1) {
                    unset($search_word_array[$sh_key]);
                    $search_word_array = array_values($search_word_array);
                }
            }

            if(count($temp) != 0) {
                $search_model[$key] = $temp;
            }
        }

        $like_clause = "";
        $like_match_clause = "";

        if(count($search_word_array) != 0) {
            $start_clause = " and ( ";
            $flag = 0;
            $search_sum = "";
            foreach($search_word_array as $like_item) {
                if($flag == 1) {
                    $like_clause .= ' or ';
                    $search_sum .= ' ';
                }
                $like_clause .= "`sku` like '%{$like_item}%' or `name` like '%{$like_item}%'" ;
                $search_sum .= $like_item;
                $flag = 1;
            }

            $like_clause = $start_clause . $like_clause;
            $like_clause .= ')';

            $replacements = [
                "oil filter" => "filter, oil",
                "gas filter" => "filter, gas"
            ];
            
            foreach ($replacements as $needle => $replacement) {
                if (strpos($search_sum, $needle) !== false) {
                    $search_sum = str_replace($needle, $replacement, $search_sum);
                }
            }

            $like_match_clause = $start_clause . "`sku` like '%{$search_sum}%' or `name` like '%{$search_sum}%' )";

            // echo $like_match_clause; exit;
        }

        $sql = "" ;
        $sql_match = "" ; 
        $flag = false ;

        foreach($search_model as $search_key => $search_item) {
            if($flag) {
                $sql.=" union all " ;
            }
            
            $sub_flag = false;
            $sub_sql = "( ";
            $sub_sql_match = "";

            foreach($search_item as $sub_key => $sub_item) {
                if($sub_flag) {
                    $sub_sql.=" or ";
                }

                $sub_sql .= "subcategory_id=UPPER('{$sub_item}') ";
                $sub_flag = true;
            }

            $sub_sql_match = $sub_sql . ')' . $like_match_clause;
            $sub_sql .= ')' . $like_clause;

            $table_name = strtolower($search_key);
            
            $sql .= "select `id`, `subcategory_id`, `category_id`, `name`, `photo`, `price`, '$search_key' as `table` from `{$table_name}` where {$sub_sql} " ;
            $sql_match .= "select `id`, `subcategory_id`, `category_id`, `name`, `photo`, `price`, '$search_key' as `table` from `{$table_name}` where {$sub_sql_match} " ;

            $flag = true ;
        }

        $categories = array();
        $categories_match = false;
        $result = array();
        if($sql != "") {
            $categories =DB::select($sql) ;
            if(count($search_word_array) > 1) {
                $categories_match = DB::select($sql_match);
            }

            if($categories_match) {
                
                foreach($categories as $key => $item) {
                    $flag = false;
                    foreach($categories_match as $sub_item) {
                        if($item->id == $sub_item->id) {
                            $flag = true;
                            break;
                        }
                    }

                    if($flag == true) {
                        unset($categories[$key]);
                        $categories = array_values($categories);
                    }
                }

                $result = array_merge($categories_match, $categories);
            }
            else {
                $result = $categories;
            }

            $data = array();
            foreach($result as $key => $item) {
                $table_name = strtolower($item->table);
                $sql = "select * from `{$table_name}_categories` where `group_Id`='{$item->category_id}' and `model`='{$item->subcategory_id}'" ;
                $ret = DB::select($sql) ;
                if($ret) {
                    $item->group_name = $ret[0]->group_name ;
                    $item->section = $ret[0]->section_name ;
                    $data[$key] = $item ;
                }
            }
            $result = $data ;
        }

        echo json_encode($result) ;
    }
}

?>