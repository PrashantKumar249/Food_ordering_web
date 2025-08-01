<?php if (!isset($row)) return; ?>
<div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
    <div class="relative">
        <img class="w-full h-48 object-cover" src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" />
        <div class="absolute top-4 right-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo ($row['category'] === 'Non-Veg') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                <i class="fas <?php echo ($row['category'] === 'Non-Veg') ? 'fa-drumstick-bite' : 'fa-leaf'; ?> mr-1"></i>
                <?php echo htmlspecialchars($row['category']); ?>
            </span>
        </div>
    </div>
    
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($row['name']); ?></h3>
        <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars($row['description']); ?></p>

        <div class="flex items-center justify-between mb-4">
            <span class="text-2xl font-bold text-orange-600">₹<?php echo number_format($row['price']); ?></span>
            <div class="flex items-center space-x-2">
                <?php
                $cart_count = 0;
                if (isset($_SESSION['user_id'])) {
                    $cart_query = "SELECT quantity as item_count FROM cart_items WHERE user_id = " . intval($_SESSION['user_id']) . " AND menu_item_id = " . intval($row['id']);
                    $cart_result = mysqli_query($conn, $cart_query);
                    $cart_data = mysqli_fetch_assoc($cart_result);
                    $cart_count = isset($cart_data['item_count']) ? $cart_data['item_count'] : 0;
                }
                ?>
                <span class="text-sm text-gray-500"><?php echo $cart_count; ?> in cart</span>
            </div>
        </div>

        <?php if (isset($row['stock_qty']) && $row['stock_qty'] == 0): ?>
            <div class="mb-4">
                <span class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded font-semibold">Out of Stock</span>
            </div>
        <?php endif; ?>

        <div class="flex items-center justify-between">
            <!-- Remove -->
            <?php if (isset($_SESSION['user_id'])) { ?>
                <form method="post" action="add_cart.php" class="inline-block">
                    <input type="hidden" name="type" value="remove">
                    <input type="hidden" name="menu_item_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="flex items-center justify-center w-12 h-12 bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 rounded-lg border-2 border-gray-200 hover:border-red-300 transition-all duration-200 hover:shadow-md" title="Remove from Cart"
                        <?php echo (isset($row['stock_qty']) && $row['stock_qty'] == 0) ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''; ?>>
                        <i class="fas fa-minus text-sm"></i>
                    </button>
                </form>
            <?php } else { ?>
                <a href="login.php" class="flex items-center justify-center w-12 h-12 bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 rounded-lg border-2 border-gray-200 hover:border-red-300 transition-all duration-200 hover:shadow-md" title="Login to remove from Cart"
                    <?php echo (isset($row['stock_qty']) && $row['stock_qty'] == 0) ? 'style="pointer-events:none;opacity:0.5;cursor:not-allowed;"' : ''; ?>>
                    <i class="fas fa-minus text-sm"></i>
                </a>
            <?php } ?>

            <!-- Add -->
            <?php if (isset($_SESSION['user_id'])) { ?>
                <form method="post" action="add_cart.php" class="inline-block">
                    <input type="hidden" name="type" value="add">
                    <input type="hidden" name="menu_item_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="flex items-center justify-center w-12 h-12 bg-orange-100 hover:bg-orange-200 text-orange-600 hover:text-orange-700 rounded-lg border-2 border-orange-200 hover:border-orange-300 transition-all duration-200 hover:shadow-md" title="Add to Cart"
                        <?php echo (isset($row['stock_qty']) && $row['stock_qty'] == 0) ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''; ?>>
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                </form>
            <?php } else { ?>
                <a href="login.php" class="flex items-center justify-center w-12 h-12 bg-orange-100 hover:bg-orange-200 text-orange-600 hover:text-orange-700 rounded-lg border-2 border-orange-200 hover:border-orange-300 transition-all duration-200 hover:shadow-md" title="Login to add to Cart"
                    <?php echo (isset($row['stock_qty']) && $row['stock_qty'] == 0) ? 'style="pointer-events:none;opacity:0.5;cursor:not-allowed;"' : ''; ?>>
                    <i class="fas fa-plus text-sm"></i>
                </a>
            <?php } ?>
        </div>
    </div>
</div>
