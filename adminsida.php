<?php
$host = "localhost";
$port = 3306;
$database = "slutprojekt";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lägg till rabattkod
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['discount_code'])) {
    $discount_code = $_POST['discount_code'];
    $discount_amount = $_POST['discount_amount'];

    $insert_discount_sql = "INSERT INTO discount_codes (code, amount) VALUES ('$discount_code', '$discount_amount')";

    if ($conn->query($insert_discount_sql) === TRUE) {
        echo "Rabattkod tillagd!";
    } else {
        echo "Error adding discount code: " . $conn->error;
    }
}

// Lägg till fraktalternativ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['shipping_option'])) {
    $shipping_option = $_POST['shipping_option'];
    $shipping_cost = $_POST['shipping_cost'];

    $insert_shipping_sql = "INSERT INTO shipping_options (name, cost) VALUES ('$shipping_option', '$shipping_cost')";

    if ($conn->query($insert_shipping_sql) === TRUE) {
        echo "Fraktalternativ tillagt!";
    } else {
        echo "Error adding shipping option: " . $conn->error;
    }
}

// Hanterar uppdatering av fraktalternativ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_shipping') {
    $order_id_to_update = $_POST['order_id'];
    $new_shipping_option = $_POST['new_shipping_option'];

    $update_shipping_sql = "UPDATE orders SET shipping_option = '$new_shipping_option' WHERE id = '$order_id_to_update'";

    if ($conn->query($update_shipping_sql) === TRUE) {
        echo "Fraktalternativ uppdaterat!";
    } else {
        echo "Error updating shipping option: " . $conn->error;
    }
}

// Hanterar borttagning av order
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete_order' && isset($_GET['id'])) {
    $order_id_to_delete = $_GET['id'];

    // Tar först bort relaterade orderobjekt
    $delete_order_items_sql = "DELETE FROM order_items WHERE order_reference = '$order_id_to_delete'";
    if ($conn->query($delete_order_items_sql) !== TRUE) {
        echo "Error deleting order items: " . $conn->error;
    }

    // Sedan tar den bort ordern
    $delete_order_sql = "DELETE FROM orders WHERE id = '$order_id_to_delete'";
    if ($conn->query($delete_order_sql) === TRUE) {
        echo "Order borttagen!";
    } else {
        echo "Error deleting order: " . $conn->error;
    }
}

// Hanterar uppdatering av status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_status' && isset($_POST['update_status_btn'])) {
    $order_id_to_update = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    $update_status_sql = "UPDATE orders SET status = '$new_status' WHERE id = '$order_id_to_update'";

    if ($conn->query($update_status_sql) === TRUE) {
        echo "Status uppdaterad!";
    } else {
        echo "Error updating status: " . $conn->error;
    }
}

// Redigera en produkt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
    $product_id_to_edit = $_POST['product_id_to_edit'];
    $new_product_name = $_POST['new_product_name'];
    $new_product_price = $_POST['new_product_price'];

    $update_product_sql = "UPDATE products SET name = '$new_product_name', price = '$new_product_price' WHERE id = '$product_id_to_edit'";
    
    if ($conn->query($update_product_sql) === TRUE) {
        echo "Produkt redigerad!";
    } else {
        echo "Error editing product: " . $conn->error;
    }
}

// Uppdatera e-postadress om formuläret har skickats
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_email'])) {
    $customer_id_to_edit = $_POST['customer_id_to_edit'];
    $new_email = $_POST['new_email'];

    $update_email_sql = "UPDATE customers SET email = '$new_email' WHERE id = '$customer_id_to_edit'";

    if ($conn->query($update_email_sql) === TRUE) {
        echo "E-postadress uppdaterad!";
    } else {
        echo "Error updating email: " . $conn->error;
    }
}

// Ta bort en produkt
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete_product' && isset($_GET['id'])) {
    $product_id_to_delete = $_GET['id'];

    $delete_product_sql = "DELETE FROM products WHERE id = '$product_id_to_delete'";
    
    if ($conn->query($delete_product_sql) === TRUE) {
        echo "Produkt borttagen!";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}

// Ta bort rabattkod
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete_discount' && isset($_GET['id'])) {
    $discount_id_to_delete = $_GET['id'];

    $delete_discount_sql = "DELETE FROM discount_codes WHERE id = '$discount_id_to_delete'";
    
    if ($conn->query($delete_discount_sql) === TRUE) {
        echo "Rabattkod borttagen!";
    } else {
        echo "Error deleting discount code: " . $conn->error;
    }
}

// Ändra text på rabattkoden och rabattbeloppet
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_discount'])) {
    $discount_id_to_edit = $_POST['discount_id_to_edit'];
    $new_discount_code = $_POST['new_discount_code'];
    $new_discount_amount = $_POST['new_discount_amount'];

    $update_discount_sql = "UPDATE discount_codes SET code = '$new_discount_code', amount = '$new_discount_amount' WHERE id = '$discount_id_to_edit'";
    
    if ($conn->query($update_discount_sql) === TRUE) {
        echo "Rabattkod redigerad!";
    } else {
        echo "Error editing discount code: " . $conn->error;
    }
}

// Hanterar borttagning av fraktalternativ
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete_shipping_option' && isset($_GET['id'])) {
    $shipping_option_id_to_delete = $_GET['id'];

    $delete_shipping_option_sql = "DELETE FROM shipping_options WHERE id = '$shipping_option_id_to_delete'";

    if ($conn->query($delete_shipping_option_sql) === TRUE) {
        echo "Fraktalternativ borttaget!";
    } else {
        echo "Error deleting shipping option: " . $conn->error;
    }
}

// Hanterar uppdatering av fraktalternativ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_shipping_option'])) {
    $shipping_option_id_to_edit = $_POST['shipping_option_id_to_edit'];
    $new_shipping_option_name = $_POST['new_shipping_option_name'];
    $new_shipping_option_cost = $_POST['new_shipping_option_cost'];

    $update_shipping_option_sql = "UPDATE shipping_options SET name = '$new_shipping_option_name', cost = '$new_shipping_option_cost' WHERE id = '$shipping_option_id_to_edit'";

    if ($conn->query($update_shipping_option_sql) === TRUE) {
        echo "Fraktalternativ redigerat!";
    } else {
        echo "Error editing shipping option: " . $conn->error;
    }
}
// Lägg till produkt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_name'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    $insert_product_sql = "INSERT INTO products (name, price) VALUES ('$product_name', '$product_price')";

    if ($conn->query($insert_product_sql) === TRUE) {
        echo "Produkt tillagd!";
    } else {
        echo "Error adding product: " . $conn->error;
    }
}

// Lista alla ordrar, ordervaror och kunder i datumordning
$sql = "SELECT orders.*, customers.first_name, customers.last_name, customers.email
        FROM orders
        INNER JOIN customers ON orders.customer_reference = customers.id
        ORDER BY orders.order_date DESC";

$result = $conn->query($sql);

// Lista alla produkter
$sql_products = "SELECT * FROM products";
$result_products = $conn->query($sql_products);

// Lista alla rabattkoder
$sql_discounts = "SELECT * FROM discount_codes";
$result_discounts = $conn->query($sql_discounts);

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adminsida @ GritStore</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Adminsida @ GritStore</h2>
    <div class="form-container">
        <form class="product_form" method="post" action="">
            <label for="product_name">Produktnamn:</label>
            <input type="text" name="product_name" required>

            <label for="product_price">Pris:</label>
            <input type="text" name="product_price" required>

            <button type="submit">Lägg till produkt</button>
        </form>
        <!-- Formulär för att lägga till rabattkod -->
        <form class="discount_form" method="post" action="">
            <label for="discount_code">Rabattkod:</label>
            <input type="text" name="discount_code" required>

            <label for="discount_amount">Rabattbelopp:</label>
            <input type="text" name="discount_amount" required>

            <button type="submit">Lägg till rabattkod</button>
        </form>

        <!-- Formulär för att lägga till fraktalternativ -->
        <form class="shipping_form" method="post" action="">
            <label for="shipping_option">Fraktalternativ:</label>
            <input type="text" name="shipping_option" required>

            <label for="shipping_cost">Fraktkostnad:</label>
            <input type="text" name="shipping_cost" required>

            <button type="submit">Lägg till fraktalternativ</button>
        </form>
    </div>

    <!-- Lista över fraktalternativ -->
    <h3>Fraktalternativ:</h3>
    <table border="1">
        <tr>
            <th>Fraktalternativ</th>
            <th>Fraktkostnad</th>
            <th>Åtgärder</th>
        </tr>
        <?php
        // Hämta fraktalternativ från databasen
        $sql_shipping_options = "SELECT * FROM shipping_options";
        $result_shipping_options = $conn->query($sql_shipping_options);

        while ($row_shipping_option = $result_shipping_options->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row_shipping_option['name']}</td>";
            echo "<td>{$row_shipping_option['cost']} kr</td>";
            echo "<td>
                    <a href='?action=delete_shipping_option&id={$row_shipping_option['id']}'>Ta bort</a> | 
                    <form method='post' action=''>
                        <input type='hidden' name='edit_shipping_option' value='true'>
                        <input type='hidden' name='shipping_option_id_to_edit' value='{$row_shipping_option['id']}'>
                        <label for='new_shipping_option_name'>Nytt fraktalternativ:</label>
                        <input type='text' name='new_shipping_option_name' required>
                        <label for='new_shipping_option_cost'>Ny fraktkostnad:</label>
                        <input type='text' name='new_shipping_option_cost' required>
                        <button type='submit'>Redigera</button>
                    </form>
                </td>";
            echo "</tr>";
        }
        ?>
    </table>



    <!-- Formulär för att ta bort rabattkod -->
    <h3>Rabattkoder:</h3>
    <table border="1">
        <tr>
            <th>Rabattkod</th>
            <th>Rabattbelopp</th>
            <th>Åtgärder</th>
        </tr>
        <?php
        $result_discounts = $conn->query($sql_discounts); 
        while ($row_discounts = $result_discounts->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row_discounts['code']}</td>";
            echo "<td>{$row_discounts['amount']} kr</td>";
            echo "<td>
                    <a href='?action=delete_discount&id={$row_discounts['id']}'>Ta bort</a> | 
                    <form method='post' action=''>
                        <input type='hidden' name='edit_discount' value='true'>
                        <input type='hidden' name='discount_id_to_edit' value='{$row_discounts['id']}'>
                        <label for='new_discount_code'>Ny rabattkod:</label>
                        <input type='text' name='new_discount_code' required>
                        <label for='new_discount_amount'>Nytt rabattbelopp:</label>
                        <input type='text' name='new_discount_amount' required>
                        <button type='submit'>Redigera</button>
                    </form>
                </td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- Lista alla ordrar, ordervaror och kunder i datumordning -->
    <h3>Lista över alla ordrar:</h3>
    <table border="1">
        <tr>
            <th>Ordernummer</th>
            <th>Kund</th>
            <th>Email</th>
            <th>Status</th>
            <th>Orderdatum</th>
            <th>Totalbelopp</th>
            <th>Rabattkod</th>
            <th>Fraktalternativ</th>
            <th>Åtgärder</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['first_name']} {$row['last_name']}</td>";
            // Visa nuvarande e-postadress med redigerbart fält
            echo "<td>
                    <form method='post' action=''>
                        <input type='hidden' name='edit_email' value='true'>
                        <input type='hidden' name='customer_id_to_edit' value='{$row['customer_reference']}'>
                        <input type='text' name='new_email' value='{$row['email']}' required>
                        <button type='submit'>Uppdatera</button>
                    </form>
                </td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$row['order_date']}</td>";
            echo "<td>{$row['total_amount']} kr</td>";
            echo "<td>{$row['discount_code']}</td>";
            echo "<td>{$row['shipping_option']}</td>";
            echo "<td>
                    <form method='post' action=''>
                        <input type='hidden' name='action' value='update_shipping'>
                        <input type='hidden' name='order_id' value='{$row['id']}'>
                        <select name='new_shipping_option'>
                            <option value='standard' " . ($row['shipping_option'] == 'standard' ? 'selected' : '') . ">Standardfrakt</option>
                            <option value='express' " . ($row['shipping_option'] == 'express' ? 'selected' : '') . ">Expressfrakt</option>
                        </select>
                        <button type='submit' name='update_shipping_btn'>Uppdatera</button>
                    </form>
                </td>";
            echo "<td>
                    <a href='?action=delete_order&id={$row['id']}'>Ta bort</a> | 
                    <form method='post' action=''>
                    <input type='hidden' name='action' value='update_status'>
                    <input type='hidden' name='order_id' value='{$row['id']}'>
                    <select name='new_status'>
                        <option value='Processing' " . ($row['status'] == 'Processing' ? 'selected' : '') . ">Processing</option>
                        <option value='Completed' " . ($row['status'] == 'Completed' ? 'selected' : '') . ">Completed</option>
                        <option value='Shipped' " . ($row['status'] == 'Shipped' ? 'selected' : '') . ">Shipped</option>
                        <option value='Canceled' " . ($row['status'] == 'Canceled' ? 'selected' : '') . ">Canceled</option>
                    </select>
                    <button type='submit' name='update_status_btn'>Uppdatera status</button>
                </form>
                </td>";
            echo "</tr>";

            // Visa ordervaror och kundinformation för varje order
            $order_id = $row['id'];
            $sql_order_items = "SELECT products.name AS product_name, order_items.quantity, order_items.amount
                                FROM order_items
                                INNER JOIN products ON order_items.product_reference = products.id
                                WHERE order_items.order_reference = $order_id";
            $result_order_items = $conn->query($sql_order_items);

            echo "<tr>";
            echo "<td colspan='9'><strong>Ordervaror:</strong></td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Produkt</td>";
            echo "<td>Antal</td>";
            echo "<td>Pris</td>";
            echo "</tr>";

            while ($row_order_item = $result_order_items->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row_order_item['product_name']}</td>";
                echo "<td>{$row_order_item['quantity']}</td>";
                echo "<td>{$row_order_item['amount']} kr</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>

    <!-- Lista alla produkter -->
    <h3>Lista över alla produkter:</h3>
    <table border="1">
        <tr>
            <th>ProduktID</th>
            <th>Produktnamn</th>
            <th>Pris</th>
            <th>Åtgärder</th>
        </tr>
        <?php
        while ($row_products = $result_products->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row_products['id']}</td>";
            echo "<td>{$row_products['name']}</td>";
            echo "<td>{$row_products['price']} kr</td>";
            echo "<td>
                    <form method='post' action=''>
                        <input type='hidden' name='edit_product' value='true'>
                        <input type='hidden' name='product_id_to_edit' value='{$row_products['id']}'>
                        <label for='new_product_name'>Nytt namn:</label>
                        <input type='text' name='new_product_name' required>
                        <label for='new_product_price'>Nytt pris:</label>
                        <input type='text' name='new_product_price' required>
                        <button type='submit'>Redigera</button>
                    </form> | 
                    <a href='?action=delete_product&id={$row_products['id']}'>Ta bort</a>
                </td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>