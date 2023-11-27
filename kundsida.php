
<?php
$host = "localhost";
$port = 3306;
$database = "slutprojekt";
$username = "root";
$password = "";

// Skapar en ny MySQLi-anslutning
$conn = new mysqli($host, $username, $password, $database, $port);

// Kontrollerar anslutningen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --------------------------------------------- Start av CREATE TABLES ---------------------------------------------
// Skapar tabell för media om den inte redan finns
$sql = "CREATE TABLE IF NOT EXISTS media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(255) NOT NULL
)";

// Utför SQL-frågan för media
if ($conn->query($sql) === TRUE) {
    echo "Table 'media' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Skapar tabell för customers
$sql = "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    personal_number VARCHAR(12) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    city VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'customers' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Skapar tabell för produkter
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    media_reference INT,
    FOREIGN KEY (media_reference) REFERENCES media(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'products' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Skapar tabell för ordrar
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_reference INT,
    status ENUM('Processing', 'Completed', 'Shipped', 'Canceled') DEFAULT 'Processing',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL,
    discount_code VARCHAR(20),
    shipping_option VARCHAR(50) NOT NULL,
    FOREIGN KEY (customer_reference) REFERENCES customers(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'orders' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Skapar tabell för ordervaror
$sql = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_reference INT,
    product_reference INT,
    quantity INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_reference) REFERENCES orders(id),
    FOREIGN KEY (product_reference) REFERENCES products(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'order_items' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Skapar tabell för rabattkoder
$sql = "CREATE TABLE IF NOT EXISTS discount_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL,
    amount DECIMAL(5, 2) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'discount_codes' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Skapar tabell för fraktoptioner om den inte redan finns
$sql_shipping_options = "CREATE TABLE IF NOT EXISTS shipping_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    cost DECIMAL(10, 2) NOT NULL
)";

if ($conn->query($sql_shipping_options) === TRUE) {
    echo "Table 'shipping_options' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// --------------------------------------------- End of CREATE TABLES ---------------------------------------------

// Lägger till media för varje produkt
$media_paths = [
    'processor1.jpg',
    'processor2.jpg',
    'processor3.jpg',
    'processor4.jpg',
    'processor5.jpg',
];

foreach ($media_paths as $file_path) {
    // Kontrollerar om filen redan finns i databasen
    $check_media_sql = "SELECT id FROM media WHERE file_path = '$file_path'";
    $existing_media_result = $conn->query($check_media_sql);

    if ($existing_media_result->num_rows == 0) {
        // Bilden finns inte i databasen, lägger till den
        $insert_media_sql = "INSERT INTO media (file_path) VALUES ('$file_path')";
        $conn->query($insert_media_sql);
    } else {
        // Bilden finns redan i databasen, skicka ett felmeddelande
        echo "Bilden '$file_path' finns redan i databasen.";
    }
}

// Lägger till produkter
$products_data = [
    ['AMD Ryzen 7 7800X3D 4,2GHz Socket AM5 Box', 4390, 1],
    ['AMD Ryzen 7 5800X3D 3,4GHz Socket AM4 Box without Cooler', 3933, 2],
    ['AMD Ryzen 5 7600X 4,7GHz Socket AM5 Box', 2690, 3],
    ['AMD Ryzen 5 5600X 3,7GHz Socket AM4 Box', 1814, 4],
    ['Intel Core i7 14700K 3,4GHz Socket 1700 Box', 5473, 5],
];

foreach ($products_data as $product) {
    $name = $product[0];
    $price = $product[1];
    $media_id = $product[2];

    // Kontrollerar om produkten redan finns i databasen baserat på namnet
    $check_product_sql = "SELECT id FROM products WHERE name = '$name'";
    $existing_product_result = $conn->query($check_product_sql);

    if ($existing_product_result->num_rows == 0) {
        // Produkten finns inte i databasen, lägger till den
        $insert_product_sql = "INSERT INTO products (name, price, media_reference) VALUES ('$name', '$price', '$media_id')";
        $conn->query($insert_product_sql);
    } else {
        // Produkten finns redan i databasen, skickar ett felmeddelande
        echo "Produkten '$name' finns redan i databasen.";
    }
}

// Hanterar order här
$order_message = ""; // Variabel för att hålla ordern lagd meddelande
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lägg till kunden i databasen
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $personal_number = $_POST['personal_number'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $postal_code = $_POST['postal_code'];
    $city = $_POST['city'];
    $email = $_POST['email'];

    $insert_customer_sql = "INSERT INTO customers (first_name, last_name, personal_number, phone_number, address, postal_code, city, email)
                            VALUES ('$first_name', '$last_name', '$personal_number', '$phone_number', '$address', '$postal_code', '$city', '$email')";

    if ($conn->query($insert_customer_sql) === TRUE) {
        echo "Customer added successfully";
    } else {
        echo "Error adding customer: " . $conn->error;
    }

    // Lägger till order i databasen
    $customer_reference = $conn->insert_id; // Hämtar ID för den senaste insatta kunden
    $status = "Processing";
    $total_amount = 0; 

    $insert_order_sql = "INSERT INTO orders (customer_reference, status, total_amount, discount_code, shipping_option)
                        VALUES ('$customer_reference', '$status', '$total_amount', '{$_POST['discount_code']}', '{$_POST['shipping_option']}')";

    if ($conn->query($insert_order_sql) === TRUE) {
        echo "Order added successfully";
    } else {
        echo "Error adding order: " . $conn->error;
    }

    // Hämtar ID för den senaste insatta ordern
    $order_reference = $conn->insert_id;

    // Lägger till ordervaror i databasen
    if (isset($_POST['products']) && is_array($_POST['products'])) {
        foreach ($_POST['products'] as $product_id => $quantity) {
            // Hämtar produktpris från databasen
            $get_product_price_sql = "SELECT price FROM products WHERE id = $product_id";
            $product_result = $conn->query($get_product_price_sql);
            $product_row = $product_result->fetch_assoc();
            $product_price = $product_row['price'];

            // Lägger till ordervara i databasen
            $insert_order_item_sql = "INSERT INTO order_items (order_reference, product_reference, quantity, amount)
                                      VALUES ('$order_reference', '$product_id', '$quantity', '$product_price')";

            if ($conn->query($insert_order_item_sql) === TRUE) {
                echo "Order item added successfully";
            } else {
                echo "Error adding order item: " . $conn->error;
            }

            // Uppdaterar totalbelopp för ordern
            $total_amount += $quantity * $product_price;
        }

        // Uppdaterar totalbelopp för ordern i databasen
        $update_order_total_sql = "UPDATE orders SET total_amount = '$total_amount' WHERE id = '$order_reference'";

        if ($conn->query($update_order_total_sql) === TRUE) {
            echo "Order total updated successfully";
        } else {
            echo "Error updating order total: " . $conn->error;
        }
    }

    // Hämtar och tillämpar rabattkod
    $discount_code = $_POST['discount_code'];

    // Kontrollerar om rabattkoden finns i databasen
    $check_discount_sql = "SELECT id, amount FROM discount_codes WHERE code = '$discount_code'";
    $discount_result = $conn->query($check_discount_sql);

    if ($discount_result->num_rows > 0) {
        $discount_row = $discount_result->fetch_assoc();
        $discount_amount = $discount_row['amount'];

        // Applicerar rabatten på totalbeloppet
        $total_amount -= $discount_amount;

        // Uppdaterar totalbelopp för ordern i databasen
        $update_order_total_sql = "UPDATE orders SET total_amount = '$total_amount' WHERE id = '$order_reference'";

        if ($conn->query($update_order_total_sql) === TRUE) {
            echo "Rabattkod tillämpad! Nytt totalbelopp: $total_amount";
        } else {
            echo "Error updating order total: " . $conn->error;
        }
    } else {
        echo "Ogiltig rabattkod. Försök igen.";
    }

    $order_message = "Order lagd! Ordernummer: $order_reference";
}

// Hämtar produkter från databasen
$sql = "SELECT products.*, media.file_path FROM products
        INNER JOIN media ON products.media_reference = media.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>GritStore</title>
</head>
<body>
    <h2>Välkommen till GritStore</h2>
    
    <!-- Formulär för att skapa en order -->
    <form method="post" action="">
        <div class="product-card-container">
            <?php
            // Visar produkter
            while ($row = $result->fetch_assoc()) {
                echo "<div class ='product-card'>";
                echo "<img src='media/{$row['file_path']}' alt='{$row['name']}'>";
                echo "<p>{$row['name']}</p>";
                echo "<p>Pris: {$row['price']} kr</p>";
                echo "<label for='product{$row['id']}'>Antal:</label>";
                echo "<input type='number' name='products[{$row['id']}]' value='0' min='0'>";
                echo "</div>";
            }
            ?>
        </div>
        
        <!-- Ange kundinformation här -->
        <label for="first_name">Förnamn:</label>
        <input type="text" name="first_name" required>
        
        <label for="last_name">Efternamn:</label>
        <input type="text" name="last_name" required>
        
        <label for="personal_number">Personnummer:</label>
        <input type="text" name="personal_number" required>
        
        <label for="phone_number">Telefonnummer:</label>
        <input type="text" name="phone_number" required>
        
        <label for="address">Address:</label>
        <input type="text" name="address" required>
        
        <label for="postal_code">Postnummer:</label>
        <input type="text" name="postal_code" required>
        
        <label for="city">Stad:</label>
        <input type="text" name="city" required>
        
        <label for="email">Epost:</label>
        <input type="email" name="email" required>
        
        <!-- Välj fraktalternativ här -->
        <label for="shipping_option">Välj fraktalternativ:</label>
        <select name="shipping_option" required>
            <option value="standard">Standardfrakt</option>
            <option value="express">Expressfrakt</option>
        </select>
        
        <!-- Lägg till rabattkod här -->
        <label for="discount_code">Rabattkod:</label>
        <input type="text" name="discount_code">
        
        <button type="submit">Lägg order</button>
        <p><?php echo $order_message; ?></p>
    </form>
</body>
</html>