<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartResourceCollection;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $cartItems = $this->cartService->getUserCart($user);
            $total = $this->cartService->getCartTotal($user);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'items' => new CartResourceCollection($cartItems),
                    'total' => $total,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب عناصر السلة: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add item to cart.
     *
     * @param AddToCartRequest $request
     * @return JsonResponse
     */
    public function store(AddToCartRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $validated = $request->validated();

            $cartItem = $this->cartService->addToCart($user, $validated['order_id']);

            return response()->json([
                'status' => 'success',
                'message' => 'تم إضافة العنصر إلى السلة بنجاح',
                'data' => new CartResource($cartItem),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified cart item.
     *
     * @param Request $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function show(Request $request, Cart $cart): JsonResponse
    {
        try {
            $this->cartService->authorize($cart);
            $cart = $this->cartService->getCartDetails($cart);

            return response()->json([
                'status' => 'success',
                'data' => new CartResource($cart),
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() === 403 ? 403 : 500;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * Update cart item.
     *
     * @param UpdateCartRequest $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function update(UpdateCartRequest $request, Cart $cart): JsonResponse
    {
        try {
            $this->cartService->authorize($cart);
            $validated = $request->validated();
            $cartItem = $this->cartService->updateCartItem($cart, $validated);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث العنصر بنجاح',
                'data' => new CartResource($cartItem),
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() === 403 ? 403 : 500;
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث العنصر: ' . $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * Remove item from cart.
     *
     * @param Request $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function destroy(Request $request, Cart $cart): JsonResponse
    {
        try {
            $this->cartService->authorize($cart);
            $this->cartService->removeFromCart($cart);

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف العنصر من السلة بنجاح',
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() === 403 ? 403 : 500;
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف العنصر: ' . $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * Clear user's cart.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clear(Request $request): JsonResponse
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

    /**
     * Get user's cart (alternative method name).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function myCart(Request $request): JsonResponse
    {
        return $this->index($request);
    }
}
