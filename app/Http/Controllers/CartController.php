<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Get user's cart items.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $cartItems = $this->cartService->getUserCart($user);
        $total = $this->cartService->getCartTotal($user);

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $cartItems,
                'total' => $total,
            ],
        ]);
    }

    /**
     * Add item to cart.
     *
     * @param AddToCartRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddToCartRequest $request)
    {
        try {
            $user = $request->user();
            $validated = $request->validated();

            $cartItem = $this->cartService->addToCart(
                $user,
                $validated['service_id'],
                $validated['quantity']
            );

            return response()->json([
                'status' => 'success',
                'message' => 'تم إضافة العنصر إلى السلة بنجاح',
                'data' => $cartItem->load('service'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update cart item quantity.
     *
     * @param UpdateCartRequest $request
     * @param Cart $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        try {
            // Check if cart item belongs to authenticated user
            if ($cart->user_id !== $request->user()->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'غير مصرح لك بتحديث هذا العنصر',
                ], 403);
            }

            $validated = $request->validated();
            $cartItem = $this->cartService->updateCartItem($cart, $validated['quantity']);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث العنصر بنجاح',
                'data' => $cartItem->load('service'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث العنصر: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove item from cart.
     *
     * @param Request $request
     * @param Cart $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Cart $cart)
    {
        try {
            // Check if cart item belongs to authenticated user
            if ($cart->user_id !== $request->user()->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'غير مصرح لك بحذف هذا العنصر',
                ], 403);
            }

            $this->cartService->removeFromCart($cart);

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف العنصر من السلة بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف العنصر: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear user's cart.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear(Request $request)
    {
        try {
            $user = $request->user();
            $this->cartService->clearCart($user);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تفريغ السلة بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تفريغ السلة: ' . $e->getMessage(),
            ], 500);
        }
    }
}
