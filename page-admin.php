<?php /* Template Name: Custom Admin Page */ ?>
<?php
// 权限验证
if (!current_user_can('administrator')) { 
    wp_die('Access Denied: Admins only. <a href="' . home_url() . '">Go Home</a>'); 
}

$json_file = WP_CONTENT_DIR . '/uploads/products.json';
$msg = '';
$edit_product = null; 

// 1. 处理删除 (Delete)
if (isset($_GET['del'])) {
    $data = json_decode(file_get_contents($json_file), true);
    $data = array_filter($data, function($i){ return $i['id'] != $_GET['del']; });
    file_put_contents($json_file, json_encode(array_values($data), JSON_PRETTY_PRINT));
    echo "<script>window.location.href='?page_id=" . get_the_ID() . "';</script>";
    exit;
}

// 2. 处理进入编辑模式 (Get Edit Data)
if (isset($_GET['edit'])) {
    $data = json_decode(file_get_contents($json_file), true);
    foreach ($data as $d) {
        if ($d['id'] == $_GET['edit']) {
            $edit_product = $d;
            break;
        }
    }
}

// 3. 处理表单提交 (Create & Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents($json_file), true);
    if (!$data) $data = [];

    // === Update 逻辑 ===
    if (isset($_POST['update_p'])) {
        $id_to_update = $_POST['p_id'];
        foreach ($data as &$item) {
            if ($item['id'] == $id_to_update) {
                // 🔴 修复点：添加 wp_unslash() 去除自动转义的斜杠
                $item['name']        = sanitize_text_field(wp_unslash($_POST['product_name']));
                $item['price']       = floatval($_POST['price']);
                $item['image']       = sanitize_text_field(wp_unslash($_POST['image']));
                $item['description'] = sanitize_textarea_field(wp_unslash($_POST['description']));
                break;
            }
        }
        $msg = "Product Updated Successfully!";
        $edit_product = null; 
    } 
    // === Create 逻辑 ===
    else if (isset($_POST['add_p'])) {
        $new_id = !empty($data) ? end($data)['id'] + 1 : 1;
        
        $new_item = [
            'id' => $new_id, 
            // 🔴 修复点：同样在新增时也加上 wp_unslash()
            'name'        => sanitize_text_field(wp_unslash($_POST['product_name'])), 
            'description' => sanitize_textarea_field(wp_unslash($_POST['description'])), 
            'price'       => floatval($_POST['price']), 
            'image'       => sanitize_text_field(wp_unslash($_POST['image']))
        ];
        $data[] = $new_item;
        $msg = 'Product Added Successfully!';
    }
    
    // 保存 JSON 时，JSON_UNESCAPED_UNICODE 可以防止中文变乱码（可选，但推荐）
    file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if (isset($_POST['update_p'])) {
        echo "<script>alert('$msg'); window.location.href='?page_id=" . get_the_ID() . "';</script>";
        exit;
    }
}
?>

<?php get_header(); ?>
<?php get_template_part('header-part'); ?>

<div class="container">
    <h1>Admin Panel 🛠️</h1>
    <p style="color:green; font-weight:bold;"><?php echo $msg; ?></p>
    
    <div style="background:#fff; padding:20px; border-radius:10px; margin-bottom:20px; border-left: 5px solid #ff6b8b;">
        <h3><?php echo $edit_product ? 'Edit Product (ID: ' . $edit_product['id'] . ')' : 'Add New Product'; ?></h3>
        
        <form method="post">
            <?php if ($edit_product): ?>
                <input type="hidden" name="p_id" value="<?php echo $edit_product['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="product_name" required value="<?php echo $edit_product ? esc_attr($edit_product['name']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" required style="width:100%;"><?php echo $edit_product ? esc_textarea($edit_product['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="text" name="price" required value="<?php echo $edit_product ? esc_attr($edit_product['price']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>Image Path (e.g. image/1.JPG)</label>
                <input type="text" name="image" required value="<?php echo $edit_product ? esc_attr($edit_product['image']) : ''; ?>">
            </div>

            <?php if ($edit_product): ?>
                <button type="submit" name="update_p" class="btn" style="background:#2980b9;">Update Product</button>
                <a href="?page_id=<?php echo get_the_ID(); ?>" class="btn" style="background:#7f8c8d;">Cancel</a>
            <?php else: ?>
                <button type="submit" name="add_p" class="btn">Add Product</button>
            <?php endif; ?>
        </form>
    </div>

    <h3 style="margin-top:30px;">Current Inventory</h3>
    <table class="customer-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $data = json_decode(file_get_contents($json_file), true);
        if ($data) {
            foreach ($data as $d) {
                $img = $d['image'];
                if (strpos($img, 'http') === false) $img = get_stylesheet_directory_uri() . '/' . $img;

                echo "<tr>";
                echo "<td>{$d['id']}</td>";
                echo "<td><img src='$img' style='width:50px; height:50px; object-fit:cover; border-radius:4px;'></td>";
                echo "<td><strong>" . esc_html($d['name']) . "</strong><br><small>" . esc_html($d['description']) . "</small></td>";
                echo "<td>¥" . number_format($d['price'], 2) . "</td>";
                echo "<td>
                        <a href='?page_id=" . get_the_ID() . "&edit={$d['id']}' style='color:blue; margin-right:10px; font-weight:bold;'>Edit</a>
                        <a href='?page_id=" . get_the_ID() . "&del={$d['id']}' style='color:red;' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No products found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php get_template_part('footer-part'); ?>