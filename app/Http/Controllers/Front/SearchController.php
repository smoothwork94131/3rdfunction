<?php 

namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;

class SearchController extends Controller{
    
    public function index(Request $request, $key = "", $keyword = "") {
        $search_word = $keyword ;
        $category = $key ;

        $category = Category::where('name', $category)->first();
        $category_id = $category->id;
        $category_slug = $category->slug;
        $category_name = $category->name;

        $result = array();
        $products = Product::select('*')
            ->where('category_id', 'like', '%'.$category_id.'%')
            ->where(function($query) use($search_word) {
                $query->where('name', 'like', '%'.$search_word.'%')
                    ->orWhere('slug', 'like', '%'.$search_word.'%')
                    ->orWhere('sku', 'like', '%'.$search_word.'%');
            })
            ->get();

        foreach($products as $product) {
            $item['id'] = $product->id;
            $item['sku'] = $product->sku;
            $item['name'] = $product->name;
            $item['slug'] = $product->slug;
            $item['photo'] = $product->photo;
            $item['price'] = $product->price;
            $item["category_slug"] = $category_slug;
            $item["category_name"] = $category_name;

            $result[] = $item;
        }

        $products = $result ;
        return view('front.search', compact('products'));
    }
    
    public function search(Request $request) {
        $search_word = $_REQUEST['search_word'] ;
        $category = $_REQUEST['key'] ;

        $category = Category::where('name', $category)->first();
        $category_id = $category->id;
        $category_slug = $category->slug;

        $result = array();
        $products = Product::select('*')
            ->where('category_id', 'like', '%'.$category_id.'%')
            ->where(function($query) use($search_word) {
                $query->where('name', 'like', '%'.$search_word.'%')
                    ->orWhere('slug', 'like', '%'.$search_word.'%')
                    ->orWhere('sku', 'like', '%'.$search_word.'%');
            })
            ->get();

        foreach($products as $product) {
            $item['id'] = $product->id;
            $item['sku'] = $product->sku;
            $item['name'] = $product->name;
            $item['slug'] = $product->slug;
            $item['photo'] = $product->photo;
            $item['price'] = $product->price;
            $item["category_slug"] = $category_slug;

            $result[] = $item;
        }

        echo json_encode($result) ;
    }
}

?>