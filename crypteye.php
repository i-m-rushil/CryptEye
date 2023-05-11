<?php
/*
Plugin Name: CrpytEye
Description: CryptEye Pro is a powerful WordPress plugin that allows you to fetch current cryptocurrency rates using an API. With this plugin, you can easily display up-to-date rates for popular cryptocurrencies such as Bitcoin, Ethereum, and Litecoin on your WordPress site.
Version: 1.0.0
Author: Rushil Shah
Author URI: https://www.linkedin.com/in/rushil-shahh/
License: GPL2
*/

$plugin_url = plugin_dir_url( __FILE__ );
$logo_url = $plugin_url . 'assets/images/logo.png';

function cryptoliverates() {
  // API URL to fetch crypto data
  $crypto_url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&per_page=50&page=1";

  // Fetch data from the API
  $crypto_response = wp_remote_get($crypto_url);

  // Decode JSON response
  $crypto_data = json_decode(wp_remote_retrieve_body($crypto_response), true);

  // Start the table
  $table = "<div class='cryptoliverates-container'><table class='cryptoliverates-table'>";

  // Table header row
  $table .= "<thead><tr><th>Icon</th><th>Name</th><th>Symbol</th><th>Price</th><th>24h Change</th></tr></thead>";

  // Table body rows
  $table .= "<tbody>";
  foreach ($crypto_data as $coin) {
    $name = $coin['name'];
    $symbol = $coin['symbol'];
    $price = $coin['current_price'];
    $change = $coin['price_change_percentage_24h'];
    $image = $coin['image'];

    // Create table row
    $table .= "<tr><td><img src='$image' width='32' height='32'></td><td>$name</td><td>$symbol</td><td>$price</td><td>$change%</td></tr>";
  }
  $table .= "</tbody>";

  // Close the table
  $table .= "</table>";

  // Add pagination links
  $total_pages = 10; // set the total number of pages here
  $current_page = 1; // set the current page number here
  $pagination = "<div class='cryptoliverates-pagination'>";
  for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $current_page) {
      $pagination .= "<span>$i</span>";
    } else {
      $pagination .= "<a href='#'>$i</a>";
    }
  }
  $pagination .= "</div>";
  $table .= $pagination;

  // Close the container
  $table .= "</div>";

  // Return the table
  return $table;
}

// Add a shortcode to display the table
add_shortcode('crypteye', 'cryptoliverates');

// Add styles for the table and pagination
function cryptoliverates_styles() {
  ?>
  <style>
    .cryptoliverates-container {
      border: 25px solid #ddd;
      margin: 0 auto;
      text-align: center;
      width: 80%;
      max-width: 1200px;
    }

    @media (max-width: 767px) {
      .cryptoliverates-container {
        border-width: 10px;
      }
    }

    .cryptoliverates-table {
      border-collapse: collapse;
      width: 100%;
      margin: 0 auto;
      text-align: center;
    }
    
    .cryptoliverates-table th, .cryptoliverates-table td {
      border: 1px solid #ddd;
      padding: 8px;
    }

    .cryptoliverates-table th {
      background-color: #f2f2f2;
    }
    
    .cryptoliverates-pagination a,
.cryptoliverates-pagination span {
  display: inline-block;
  margin-right: 8px;
  padding: 4px 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  color: #333;
  text-decoration: none;
}

.cryptoliverates-pagination span {
  background-color: #ddd;
  cursor: default;
}

.cryptoliverates-pagination a:hover {
  background-color: #ddd;
}

.cryptoliverates-pagination a.active {
  background-color: #0073aa;
  color: #fff;
  border-color: #0073aa;
}

@media (max-width: 767px) {
  .cryptoliverates-table th, .cryptoliverates-table td {
    padding: 4px;
  }
}

</style>
  <?php
}

// Enqueue styles for the table and pagination
add_action('wp_enqueue_scripts', 'cryptoliverates_styles');







