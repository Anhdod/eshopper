<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Lấy identifier hiện tại: user_id nếu đăng nhập, session_id nếu guest
     */
    private function identifier()
    {
        return Auth::check() ? 'user_id' : 'session_id';
    }

    /**
     * Lấy giá trị ID tương ứng
     */
    private function idValue()
    {
        return Auth::check() ? Auth::id() : session()->getId();
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request)
    {
        $request->validate([
            'id'       => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color'    => 'nullable|string|max:255',
            'size'     => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->id);

        // Kiểm tra tồn kho (nếu có trường stock)
        if ($product->stock !== null) {
            $currentQty = $this->getCurrentQuantity($product->id);
            if ($currentQty + $request->quantity > $product->stock) {
                return $this->jsonError("Chỉ còn {$product->stock} sản phẩm trong kho!");
            }
        }

        $identifier = $this->identifier();
        $idValue = $this->idValue();

      $cartItem = CartItem::firstOrNew([
    'product_id' => $request->id,
    $identifier  => $idValue,
    'color'      => $request->color,
    'size'       => $request->size,
        ]);

     
        $cartItem->user_id    = Auth::check() ? Auth::id() : null;
        $cartItem->session_id = session()->getId();

        $cartItem->quantity = $cartItem->exists
            ? $cartItem->quantity + $request->quantity
            : $request->quantity;
        $cartItem->color = $request->color;
        $cartItem->size = $request->size;

        $cartItem->save();


        $totalItems = $this->getTotalItems();

        return $request->expectsJson()
            ? response()->json([
                'success'     => true,
                'message'     => 'Đã thêm vào giỏ hàng!',
                'totalItems'  => $totalItems,
                'cartItemId'  => $cartItem->id
            ])
            : back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {

        
        $identifier = $this->identifier();
        $idValue = $this->idValue();

        $cartItems = CartItem::where($identifier, $idValue)
            ->with('product')
            ->get();

        return view('cart', [
            'cartItems' => $cartItems,
            'active'    => 'cart',
            'banner'    => ['SHOPPING CART', 'Cart'],
        ]);
    }

    /**
     * Tăng số lượng
     */
    public function increment($id)
    {
        $item = $this->findCartItem($id);

        if ($item->product->stock !== null && $item->quantity + 1 > $item->product->stock) {
            return $this->jsonError("Không đủ hàng trong kho!");
        }

        $item->increment('quantity');
        return $this->jsonSuccess($item);
    }

    /**
     * Giảm số lượng
     */
    public function decrement($id)
    {
        $item = $this->findCartItem($id);

        if ($item->quantity <= 1) {
            return $this->jsonError("Số lượng không thể nhỏ hơn 1!");
        }

        $item->decrement('quantity');
        return $this->jsonSuccess($item);
    }

    /**
     * Cập nhật số lượng thủ công
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
        ]);

        $item = $this->findCartItem($id);

        if ($item->product->stock !== null && $request->quantity > $item->product->stock) {
            return $this->jsonError("Chỉ còn {$item->product->stock} sản phẩm!");
        }

        $item->update([
            'quantity' => $request->quantity,
            'color' => $request->color ?? $item->color,
            'size' => $request->size ?? $item->size,
        ]);

        return $this->jsonSuccess($item);
    }

    /**
     * Xóa sản phẩm
     */
    public function remove($id)
    {
        $this->findCartItem($id)->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Tìm CartItem theo ID và owner (user/session)
     */
    private function findCartItem($id)
    {
        $identifier = $this->identifier();
        $idValue = $this->idValue();

        return CartItem::where('id', $id)
            ->where($identifier, $idValue)
            ->firstOrFail();
    }

    /**
     * Lấy tổng số lượng sản phẩm trong giỏ
     */
    private function getTotalItems()
    {
        $identifier = $this->identifier();
        $idValue = $this->idValue();

        return CartItem::where($identifier, $idValue)->sum('quantity');
    }

    /**
     * Lấy số lượng hiện tại của 1 sản phẩm trong giỏ
     */
    private function getCurrentQuantity($productId)
    {
        $identifier = $this->identifier();
        $idValue = $this->idValue();

        return CartItem::where('product_id', $productId)
            ->where($identifier, $idValue)
            ->sum('quantity');
    }

    /**
     * Response JSON thành công
     */
    private function jsonSuccess($item)
    {
        return response()->json([
            'success'  => true,
            'quantity' => $item->quantity,
            'total'    => number_format($item->product->price * $item->quantity, 2),
            'subtotal' => number_format($this->getSubtotal(), 2),
            'totalItems' => $this->getTotalItems()
        ]);
    }

    /**
     * Response JSON lỗi
     */
    private function jsonError($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], 400);
    }

    /**
     * Tính tổng tiền giỏ hàng
     */
    private function getSubtotal()
    {
        $identifier = $this->identifier();
        $idValue = $this->idValue();

        return CartItem::where($identifier, $idValue)
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
    }
}
