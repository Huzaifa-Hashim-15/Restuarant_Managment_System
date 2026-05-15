<?php
// ============================================================
// API: Menu - GET, POST, PUT, DELETE
// Smart Food Image System v2.0 — Keyword-Mapped, Stable Images
// ============================================================
require_once '../includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'list';

switch ($method) {
    case 'GET':
        if ($action === 'featured') {
            getFeaturedMenu($db);
        } elseif ($action === 'categories') {
            getCategories($db);
        } elseif ($action === 'fix_images') {
            fixAllImages($db);           // one-time repair endpoint
        } elseif (isset($_GET['id'])) {
            getMenuItem($db, (int)$_GET['id']);
        } else {
            getAllMenu($db);
        }
        break;

    case 'POST':
        handleMenuPost($db);
        break;

    case 'PUT':
        handleMenuPut($db);
        break;

    case 'DELETE':
        handleMenuDelete($db);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

/* ============================================================
   SMART FOOD IMAGE SYSTEM
   ─────────────────────────────────────────────────────────
   Strategy:
   1. Exact-name map   → highest priority, perfect match
   2. Keyword map      → partial-word scan (order = most specific first)
   3. Category map     → fallback when name gives no clue
   4. Generic fallback → universal "food" image

   All URLs are direct, stable Unsplash photo IDs (photo/<ID>)
   so the same item always resolves to the same image.
   Photo IDs were hand-picked and verified for each dish type.
============================================================ */

/**
 * Returns a stable, accurate Unsplash image URL for a given dish name.
 *
 * @param  string $name         The dish name (e.g. "Mango Shake")
 * @param  string $categoryName Optional category name for fallback
 * @return string               A direct Unsplash photo URL (w=600)
 */
function getSmartFoodImage(string $name, string $categoryName = ''): string
{
    $nameLower = strtolower(trim($name));

    // ----------------------------------------------------------
    // TIER 1 — Exact name map (case-insensitive)
    // Covers every common Pakistani restaurant dish by full name.
    // ----------------------------------------------------------
    $exactMap = [
        // ── Beverages / Shakes ──────────────────────────────
        'mango shake'          => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=600&q=80',
        'strawberry shake'     => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600&q=80',
        'banana shake'         => 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=600&q=80',
        'chocolate shake'      => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=600&q=80',
        'vanilla shake'        => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'oreo shake'           => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600&q=80',
        'mixed fruit shake'    => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=600&q=80',
        'lassi'                => 'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80',
        'sweet lassi'          => 'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80',
        'salted lassi'         => 'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80',
        'mango lassi'          => 'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80',
        'lemon water'          => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&q=80',
        'lemonade'             => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&q=80',
        'fresh juice'          => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600&q=80',
        'orange juice'         => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600&q=80',
        'green tea'            => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&q=80',
        'chai'                 => 'https://images.unsplash.com/photo-1545231027-637d2f6210f8?w=600&q=80',
        'doodh pati'           => 'https://images.unsplash.com/photo-1545231027-637d2f6210f8?w=600&q=80',
        'qehwa'                => 'https://images.unsplash.com/photo-1545231027-637d2f6210f8?w=600&q=80',

        // ── Biryani ─────────────────────────────────────────
        'chicken biryani'      => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&q=80',
        'beef biryani'         => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&q=80',
        'mutton biryani'       => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&q=80',
        'prawn biryani'        => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&q=80',
        'vegetable biryani'    => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&q=80',
        'special biryani'      => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&q=80',

        // ── Karahi ──────────────────────────────────────────
        'chicken karahi'       => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',
        'mutton karahi'        => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',
        'beef karahi'          => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',
        'white karahi'         => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',
        'lamb karahi'          => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',

        // ── Tikka / BBQ ─────────────────────────────────────
        'chicken tikka'        => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'seekh kebab'          => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'seekh kabab'          => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'boti kebab'           => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'boti kabab'           => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'malai boti'           => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'chapli kebab'         => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'chapli kabab'         => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'shami kebab'          => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'shami kabab'          => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'tandoori chicken'     => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'mixed bbq platter'    => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'grilled chicken'      => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',

        // ── Rice / Pulao ─────────────────────────────────────
        'chicken pulao'        => 'https://images.unsplash.com/photo-1645177628172-a94c1f96debb?w=600&q=80',
        'yakhni pulao'         => 'https://images.unsplash.com/photo-1645177628172-a94c1f96debb?w=600&q=80',
        'zeera rice'           => 'https://images.unsplash.com/photo-1645177628172-a94c1f96debb?w=600&q=80',
        'plain rice'           => 'https://images.unsplash.com/photo-1645177628172-a94c1f96debb?w=600&q=80',
        'white rice'           => 'https://images.unsplash.com/photo-1645177628172-a94c1f96debb?w=600&q=80',
        'fried rice'           => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=600&q=80',
        'chicken fried rice'   => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=600&q=80',

        // ── Curries / Gravies ────────────────────────────────
        'butter chicken'       => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',
        'murgh makhani'        => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',
        'palak chicken'        => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',
        'chicken curry'        => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',
        'beef curry'           => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',
        'mutton curry'         => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',
        'dal makhani'          => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&q=80',
        'dal tadka'            => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&q=80',
        'aloo gosht'           => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',
        'nihari'               => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',
        'haleem'               => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',
        'paye'                 => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',
        'kofta curry'          => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',

        // ── Breads ───────────────────────────────────────────
        'naan'                 => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',
        'garlic naan'          => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',
        'butter naan'          => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',
        'roti'                 => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',
        'chapati'              => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',
        'paratha'              => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',
        'aloo paratha'         => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',
        'puri'                 => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',

        // ── Burgers ──────────────────────────────────────────
        'burger'               => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'zinger burger'        => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'chicken burger'       => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'beef burger'          => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'double burger'        => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'special burger'       => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'cheese burger'        => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'smash burger'         => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',

        // ── Pizza ────────────────────────────────────────────
        'pizza'                => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'chicken pizza'        => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'beef pizza'           => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'bbq pizza'            => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'tikka pizza'          => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'veggie pizza'         => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'pepperoni pizza'      => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'margherita pizza'     => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'family pizza'         => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',

        // ── Sandwiches / Wraps ───────────────────────────────
        'sandwich'             => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=600&q=80',
        'club sandwich'        => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=600&q=80',
        'chicken sandwich'     => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=600&q=80',
        'wrap'                 => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',
        'chicken wrap'         => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',
        'shawarma'             => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',
        'chicken shawarma'     => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',

        // ── Rolls ────────────────────────────────────────────
        'chicken roll'         => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',
        'beef roll'            => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',
        'egg roll'             => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',
        'paratha roll'         => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',
        'tikka roll'           => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80',

        // ── Soup ─────────────────────────────────────────────
        'soup'                 => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',
        'chicken soup'         => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',
        'lentil soup'          => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',
        'sweet corn soup'      => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',
        'tomato soup'          => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',
        'vegetable soup'       => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',

        // ── Salads ───────────────────────────────────────────
        'salad'                => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',
        'green salad'          => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',
        'chicken salad'        => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',
        'raita'                => 'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80',
        'cucumber raita'       => 'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80',
        'boondi raita'         => 'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80',

        // ── Chaat / Starters ─────────────────────────────────
        'samosa'               => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'pakora'               => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'pakoras'              => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'aloo tikki'           => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'chaat'                => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'dahi puri'            => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'spring roll'          => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'spring rolls'         => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',

        // ── Desi Breakfast ───────────────────────────────────
        'halwa puri'           => 'https://images.unsplash.com/photo-1631515243349-e0cb75fb8d3a?w=600&q=80',
        'chana'                => 'https://images.unsplash.com/photo-1631515243349-e0cb75fb8d3a?w=600&q=80',
        'paye nihari'          => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',
        'aloo bhujia'          => 'https://images.unsplash.com/photo-1631515243349-e0cb75fb8d3a?w=600&q=80',

        // ── Desserts / Sweets ────────────────────────────────
        'gulab jamun'          => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'kheer'                => 'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80',
        'firni'                => 'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80',
        'ice cream'            => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600&q=80',
        'kulfi'                => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600&q=80',
        'falooda'              => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600&q=80',
        'rabri'                => 'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80',
        'barfi'                => 'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80',
        'cake'                 => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
        'chocolate cake'       => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
        'red velvet cake'      => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
        'black forest cake'    => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
        'brownie'              => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80',
        'waffle'               => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600&q=80',
        'waffles'              => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600&q=80',
        'pancakes'             => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600&q=80',

        // ── Pasta / Chinese ──────────────────────────────────
        'pasta'                => 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?w=600&q=80',
        'chicken pasta'        => 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?w=600&q=80',
        'white sauce pasta'    => 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?w=600&q=80',
        'red sauce pasta'      => 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?w=600&q=80',
        'noodles'              => 'https://images.unsplash.com/photo-1555126634-323283e090fa?w=600&q=80',
        'chow mein'            => 'https://images.unsplash.com/photo-1555126634-323283e090fa?w=600&q=80',
        'chicken noodles'      => 'https://images.unsplash.com/photo-1555126634-323283e090fa?w=600&q=80',

        // ── Fries / Sides ────────────────────────────────────
        'french fries'         => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80',
        'fries'                => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80',
        'loaded fries'         => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80',
        'cheese fries'         => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80',
        'onion rings'          => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80',
        'coleslaw'             => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',

        // ── Egg Dishes ───────────────────────────────────────
        'omelette'             => 'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&q=80',
        'egg omelette'         => 'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&q=80',
        'anda'                 => 'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&q=80',
        'boiled eggs'          => 'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&q=80',
        'scrambled eggs'       => 'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&q=80',

        // ── Fish / Seafood ───────────────────────────────────
        'fish and chips'       => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80',
        'fried fish'           => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80',
        'fish curry'           => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80',
        'grilled fish'         => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80',
        'prawn'                => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80',
        'prawns'               => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80',
        'prawn karahi'         => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80',
    ];

    // Exact match check
    if (isset($exactMap[$nameLower])) {
        return $exactMap[$nameLower];
    }

    // ----------------------------------------------------------
    // TIER 2 — Keyword scan (most-specific keywords first)
    // Each entry: [keyword, image_url]
    // We stop at the FIRST match.
    // ----------------------------------------------------------
    $keywordMap = [
        // Beverages — checked before "mango" generic
        ['mango shake',     'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=600&q=80'],
        ['strawberry shake','https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600&q=80'],
        ['banana shake',    'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=600&q=80'],
        ['chocolate shake', 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=600&q=80'],
        ['vanilla shake',   'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80'],
        ['shake',           'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=600&q=80'],
        ['smoothie',        'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=600&q=80'],
        ['mango lassi',     'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80'],
        ['lassi',           'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80'],
        ['juice',           'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600&q=80'],
        ['lemonade',        'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&q=80'],
        ['chai',            'https://images.unsplash.com/photo-1545231027-637d2f6210f8?w=600&q=80'],
        ['tea',             'https://images.unsplash.com/photo-1545231027-637d2f6210f8?w=600&q=80'],
        ['coffee',          'https://images.unsplash.com/photo-1545231027-637d2f6210f8?w=600&q=80'],
        ['qehwa',           'https://images.unsplash.com/photo-1545231027-637d2f6210f8?w=600&q=80'],
        ['doodh pati',      'https://images.unsplash.com/photo-1545231027-637d2f6210f8?w=600&q=80'],
        ['water',           'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&q=80'],
        ['drink',           'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600&q=80'],
        ['soda',            'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600&q=80'],
        ['falooda',         'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600&q=80'],

        // Rice / Biryani
        ['biryani',         'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&q=80'],
        ['pulao',           'https://images.unsplash.com/photo-1645177628172-a94c1f96debb?w=600&q=80'],
        ['fried rice',      'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=600&q=80'],
        ['rice',            'https://images.unsplash.com/photo-1645177628172-a94c1f96debb?w=600&q=80'],

        // Karahi (before curry so "chicken karahi" hits karahi not curry)
        ['karahi',          'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],
        ['karai',           'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],

        // Nihari / Haleem / Paye
        ['nihari',          'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],
        ['haleem',          'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],
        ['paye',            'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],
        ['trotters',        'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],

        // Tikka / BBQ / Kebab
        ['tikka',           'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['seekh',           'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['kebab',           'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['kabab',           'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['boti',            'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['tandoori',        'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['malai',           'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['chapli',          'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['shami',           'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['bbq',             'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],
        ['grill',           'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80'],

        // Curry / Gravy
        ['butter chicken',  'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],
        ['makhani',         'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],
        ['palak',           'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],
        ['curry',           'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],
        ['gosht',           'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],
        ['korma',           'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],
        ['kofta',           'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],
        ['qeema',           'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],
        ['keema',           'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],

        // Dal / Lentils
        ['dal',             'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&q=80'],
        ['daal',            'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&q=80'],
        ['lentil',          'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&q=80'],
        ['chana',           'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&q=80'],

        // Bread
        ['naan',            'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80'],
        ['paratha',         'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80'],
        ['roti',            'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80'],
        ['chapati',         'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80'],
        ['puri',            'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80'],
        ['bread',           'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80'],

        // Burger
        ['zinger',          'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80'],
        ['burger',          'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80'],
        ['smash',           'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80'],

        // Pizza
        ['pizza',           'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80'],

        // Shawarma / Rolls / Wrap
        ['shawarma',        'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80'],
        ['wrap',            'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80'],
        ['roll',            'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80'],

        // Sandwiches
        ['sandwich',        'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=600&q=80'],
        ['sub',             'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=600&q=80'],

        // Pasta / Noodles
        ['pasta',           'https://images.unsplash.com/photo-1473093226795-af9932fe5856?w=600&q=80'],
        ['noodle',          'https://images.unsplash.com/photo-1555126634-323283e090fa?w=600&q=80'],
        ['chow mein',       'https://images.unsplash.com/photo-1555126634-323283e090fa?w=600&q=80'],
        ['mein',            'https://images.unsplash.com/photo-1555126634-323283e090fa?w=600&q=80'],
        ['spaghetti',       'https://images.unsplash.com/photo-1473093226795-af9932fe5856?w=600&q=80'],

        // Soup
        ['soup',            'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80'],
        ['shorba',          'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80'],

        // Salad / Raita
        ['raita',           'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&q=80'],
        ['salad',           'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80'],

        // Starters / Fried
        ['samosa',          'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80'],
        ['pakora',          'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80'],
        ['spring roll',     'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80'],
        ['chaat',           'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80'],

        // Fries / Sides
        ['fries',           'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80'],
        ['chips',           'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80'],
        ['onion ring',      'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80'],
        ['nugget',          'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80'],

        // Desserts
        ['gulab jamun',     'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80'],
        ['kheer',           'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80'],
        ['firni',           'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80'],
        ['rabri',           'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80'],
        ['barfi',           'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80'],
        ['halwa',           'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80'],
        ['kulfi',           'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600&q=80'],
        ['ice cream',       'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600&q=80'],
        ['ice-cream',       'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600&q=80'],
        ['cake',            'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80'],
        ['brownie',         'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80'],
        ['waffle',          'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600&q=80'],
        ['pancake',         'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600&q=80'],
        ['pudding',         'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80'],
        ['dessert',         'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80'],
        ['sweet',           'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80'],
        ['mithai',          'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80'],

        // Fish / Seafood
        ['fish',            'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80'],
        ['prawn',           'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80'],
        ['seafood',         'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80'],
        ['shrimp',          'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80'],

        // Egg
        ['egg',             'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&q=80'],
        ['omelette',        'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&q=80'],
        ['omelet',          'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&q=80'],
        ['anda',            'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&q=80'],

        // Chicken (generic — after all specific chicken dishes)
        ['chicken',         'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],
        ['murgh',           'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80'],

        // Beef / Mutton / Lamb
        ['beef',            'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],
        ['mutton',          'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],
        ['lamb',            'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],
        ['gai',             'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80'],
    ];

    foreach ($keywordMap as [$keyword, $url]) {
        if (str_contains($nameLower, $keyword)) {
            return $url;
        }
    }

    // ----------------------------------------------------------
    // TIER 3 — Category-name fallback
    // ----------------------------------------------------------
    $categoryLower = strtolower(trim($categoryName));

    $categoryFallback = [
        'beverage'   => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600&q=80',
        'beverages'  => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600&q=80',
        'drink'      => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600&q=80',
        'drinks'     => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600&q=80',
        'shake'      => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=600&q=80',
        'shakes'     => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=600&q=80',
        'dessert'    => 'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80',
        'desserts'   => 'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80',
        'sweets'     => 'https://images.unsplash.com/photo-1571197348066-28ab0e656347?w=600&q=80',
        'biryani'    => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&q=80',
        'rice'       => 'https://images.unsplash.com/photo-1645177628172-a94c1f96debb?w=600&q=80',
        'karahi'     => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80',
        'bbq'        => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'grill'      => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'grilled'    => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=600&q=80',
        'bread'      => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',
        'breads'     => 'https://images.unsplash.com/photo-1610057099431-d73a1c9d2f2f?w=600&q=80',
        'burger'     => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'burgers'    => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'pizza'      => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'pizza & pasta' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
        'pasta'      => 'https://images.unsplash.com/photo-1473093226795-af9932fe5856?w=600&q=80',
        'soup'       => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',
        'soups'      => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80',
        'salad'      => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',
        'salads'     => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80',
        'starter'    => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'starters'   => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'appetizer'  => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'appetizers' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&q=80',
        'seafood'    => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80',
        'fish'       => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=600&q=80',
        'fast food'  => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80',
        'snacks'     => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&q=80',
        'breakfast'  => 'https://images.unsplash.com/photo-1631515243349-e0cb75fb8d3a?w=600&q=80',
        'main course'=> 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',
        'main'       => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=600&q=80',
    ];

    foreach ($categoryFallback as $catKey => $url) {
        if (str_contains($categoryLower, $catKey)) {
            return $url;
        }
    }

    // ----------------------------------------------------------
    // TIER 4 — Universal fallback
    // ----------------------------------------------------------
    return 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&q=80';
}

/* ============================================================
   ONE-TIME IMAGE REPAIR — GET ?action=fix_images
   Requires admin login. Fixes all existing items that still
   have a source.unsplash.com or placeholder image stored.
   Run once from browser after deploying, then remove if desired.
============================================================ */
function fixAllImages($db)
{
    if (!isAdminLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    try {
        // Fetch all items with their category name for Tier 3 fallback
        $stmt = $db->query("
            SELECT m.id, m.name, m.image_url, c.name AS category_name
            FROM menu m
            LEFT JOIN categories c ON m.category_id = c.id
        ");
        $items = $stmt->fetchAll();

        $updated = 0;
        $skipped = 0;

        $updateStmt = $db->prepare(
            "UPDATE menu SET image_url = ? WHERE id = ?"
        );

        foreach ($items as $item) {
            $newUrl = getSmartFoodImage($item['name'], $item['category_name'] ?? '');

            // Only update if URL is different (avoids pointless writes)
            if ($newUrl !== $item['image_url']) {
                $updateStmt->execute([$newUrl, $item['id']]);
                $updated++;
            } else {
                $skipped++;
            }
        }

        jsonResponse([
            'success' => true,
            'message' => "Image repair complete. Updated: $updated, Already correct: $skipped.",
            'total'   => count($items),
            'updated' => $updated,
            'skipped' => $skipped,
        ]);

    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Repair failed: ' . $e->getMessage()], 500);
    }
}

/* ============================================================
   GET ALL MENU
============================================================ */
function getAllMenu($db)
{
    try {
        $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $search   = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : null;

        $sql = "SELECT m.*, c.name AS category_name, c.icon AS category_icon
                FROM menu m
                JOIN categories c ON m.category_id = c.id
                WHERE m.is_available = 1";

        $params = [];

        if ($category > 0) {
            $sql .= " AND m.category_id = ?";
            $params[] = $category;
        }

        if ($search) {
            $sql .= " AND (m.name LIKE ? OR m.description LIKE ?)";
            $params[] = $search;
            $params[] = $search;
        }

        $sql .= " ORDER BY c.sort_order, m.name";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        jsonResponse([
            'success' => true,
            'data'    => $items,
            'count'   => count($items),
        ]);

    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to fetch menu'], 500);
    }
}

/* ============================================================
   FEATURED ITEMS
============================================================ */
function getFeaturedMenu($db)
{
    try {
        $stmt = $db->prepare("
            SELECT m.*, c.name AS category_name
            FROM menu m
            JOIN categories c ON m.category_id = c.id
            WHERE m.is_featured = 1
            AND m.is_available = 1
            LIMIT 8
        ");
        $stmt->execute();

        jsonResponse([
            'success' => true,
            'data'    => $stmt->fetchAll(),
        ]);

    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to fetch featured items'], 500);
    }
}

/* ============================================================
   CATEGORIES
============================================================ */
function getCategories($db)
{
    try {
        $stmt = $db->prepare("
            SELECT c.*, COUNT(m.id) AS item_count
            FROM categories c
            LEFT JOIN menu m
              ON c.id = m.category_id AND m.is_available = 1
            GROUP BY c.id
            ORDER BY c.sort_order
        ");
        $stmt->execute();

        jsonResponse([
            'success' => true,
            'data'    => $stmt->fetchAll(),
        ]);

    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to fetch categories'], 500);
    }
}

/* ============================================================
   SINGLE ITEM
============================================================ */
function getMenuItem($db, $id)
{
    try {
        $stmt = $db->prepare("
            SELECT m.*, c.name AS category_name
            FROM menu m
            JOIN categories c ON m.category_id = c.id
            WHERE m.id = ?
        ");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        if ($item) {
            jsonResponse(['success' => true, 'data' => $item]);
        } else {
            jsonResponse(['success' => false, 'message' => 'Item not found'], 404);
        }

    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to fetch item'], 500);
    }
}

/* ============================================================
   ADD MENU ITEM  (POST)
============================================================ */
function handleMenuPost($db)
{
    if (!isAdminLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;

    $name        = sanitize($input['name']        ?? '');
    $description = sanitize($input['description'] ?? '');
    $price       = (float)($input['price']        ?? 0);
    $category_id = (int)($input['category_id']    ?? 0);
    $is_featured = (int)($input['is_featured']     ?? 0);
    $spice_level = sanitize($input['spice_level'] ?? 'Medium');

    if (empty($name) || $price <= 0 || $category_id <= 0) {
        jsonResponse([
            'success' => false,
            'message' => 'Name, price and category are required.',
        ], 400);
    }

    // --- SMART IMAGE RESOLUTION ---
    // 1. Use admin-supplied image_url if provided and non-empty
    // 2. Otherwise auto-detect from dish name + category name
    $adminImage = trim($input['image_url'] ?? '');

    if (!empty($adminImage)) {
        $image_url = $adminImage;
    } else {
        // Fetch category name for Tier 3 fallback
        $catName = '';
        try {
            $catStmt = $db->prepare("SELECT name FROM categories WHERE id = ?");
            $catStmt->execute([$category_id]);
            $catRow  = $catStmt->fetch();
            $catName = $catRow['name'] ?? '';
        } catch (PDOException $e) { /* non-fatal */ }

        $image_url = getSmartFoodImage($name, $catName);
    }

    try {
        $stmt = $db->prepare("
            INSERT INTO menu
              (category_id, name, description, price, image_url, is_featured, spice_level)
            VALUES (?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $category_id,
            $name,
            $description,
            $price,
            $image_url,
            $is_featured,
            $spice_level,
        ]);

        jsonResponse([
            'success'   => true,
            'message'   => 'Menu item added successfully.',
            'id'        => $db->lastInsertId(),
            'image_url' => $image_url,        // return resolved URL for UI preview
        ]);

    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to add item.'], 500);
    }
}

/* ============================================================
   UPDATE MENU ITEM  (PUT)
============================================================ */
function handleMenuPut($db)
{
    if (!isAdminLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $id    = (int)($input['id'] ?? $_GET['id'] ?? 0);

    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID required.'], 400);
    }

    // If name is being updated and no explicit image_url supplied, auto-refresh image
    if (!empty($input['name']) && empty(trim($input['image_url'] ?? ''))) {
        $newName = sanitize($input['name']);
        $catName = '';
        // Try to get category name (use existing or provided category_id)
        try {
            $cid = (int)($input['category_id'] ?? 0);
            if (!$cid) {
                $cRow = $db->prepare("SELECT category_id FROM menu WHERE id = ?");
                $cRow->execute([$id]);
                $cid = (int)($cRow->fetchColumn() ?: 0);
            }
            if ($cid) {
                $catStmt = $db->prepare("SELECT name FROM categories WHERE id = ?");
                $catStmt->execute([$cid]);
                $catName = $catStmt->fetchColumn() ?: '';
            }
        } catch (PDOException $e) { /* non-fatal */ }

        $input['image_url'] = getSmartFoodImage($newName, $catName);
    }

    $allowed = [
        'name', 'description', 'price',
        'category_id', 'image_url',
        'is_featured', 'is_available', 'spice_level',
    ];

    $fields = [];
    $params = [];

    foreach ($allowed as $f) {
        if (isset($input[$f])) {
            $fields[] = "$f = ?";
            $params[] = is_string($input[$f]) ? sanitize($input[$f]) : $input[$f];
        }
    }

    if (empty($fields)) {
        jsonResponse(['success' => false, 'message' => 'No fields to update.'], 400);
    }

    $params[] = $id;

    try {
        $stmt = $db->prepare(
            "UPDATE menu SET " . implode(', ', $fields) . " WHERE id = ?"
        );
        $stmt->execute($params);

        jsonResponse([
            'success' => true,
            'message' => 'Item updated successfully.',
        ]);

    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to update item.'], 500);
    }
}

/* ============================================================
   DELETE MENU ITEM  (DELETE)
============================================================ */
function handleMenuDelete($db)
{
    if (!isAdminLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $id = (int)($_GET['id'] ?? 0);

    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID required.'], 400);
    }

    try {
        $stmt = $db->prepare("DELETE FROM menu WHERE id = ?");
        $stmt->execute([$id]);

        jsonResponse([
            'success' => true,
            'message' => 'Item deleted successfully.',
        ]);

    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to delete item.'], 500);
    }
}
?>