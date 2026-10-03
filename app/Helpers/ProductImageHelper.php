<?php

namespace App\Helpers;

class ProductImageHelper
{
    /**
     * Keyword → Unsplash image URL map.
     * Images are matched by scanning the product name (case-insensitive).
     * Each entry maps a keyword to a direct, reliable image URL.
     */
    protected static array $keywordMap = [
        // Computers & Laptops
        'laptop'       => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&q=80&fit=crop',
        'notebook'     => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&q=80&fit=crop',
        'macbook'      => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&q=80&fit=crop',
        'desktop'      => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=600&q=80&fit=crop',
        'computer'     => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=600&q=80&fit=crop',
        'pc'           => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=600&q=80&fit=crop',
        'imac'         => 'https://images.unsplash.com/photo-1527443224154-c4a573d81be4?w=600&q=80&fit=crop',

        // Input Devices
        'mouse'        => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=600&q=80&fit=crop',
        'keyboard'     => 'https://images.unsplash.com/photo-1601445638532-3c6f6c3aa1d6?w=600&q=80&fit=crop',
        'trackpad'     => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=600&q=80&fit=crop',
        'joystick'     => 'https://images.unsplash.com/photo-1600861194942-f883de0dfe96?w=600&q=80&fit=crop',
        'controller'   => 'https://images.unsplash.com/photo-1600861194942-f883de0dfe96?w=600&q=80&fit=crop',
        'gamepad'      => 'https://images.unsplash.com/photo-1600861194942-f883de0dfe96?w=600&q=80&fit=crop',

        // Displays
        'monitor'      => 'https://images.unsplash.com/photo-1616763355548-1b606f439f86?w=600&q=80&fit=crop',
        'display'      => 'https://images.unsplash.com/photo-1616763355548-1b606f439f86?w=600&q=80&fit=crop',
        'screen'       => 'https://images.unsplash.com/photo-1616763355548-1b606f439f86?w=600&q=80&fit=crop',
        'tv'           => 'https://images.unsplash.com/photo-1509281373149-e957c6296406?w=600&q=80&fit=crop',
        'television'   => 'https://images.unsplash.com/photo-1509281373149-e957c6296406?w=600&q=80&fit=crop',
        'projector'    => 'https://images.unsplash.com/photo-1601944179066-29786cb9d32a?w=600&q=80&fit=crop',

        // Audio
        'headset'      => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80&fit=crop',
        'headphone'    => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80&fit=crop',
        'earphone'     => 'https://images.unsplash.com/photo-1572536147248-ac59a8abfa4b?w=600&q=80&fit=crop',
        'earbuds'      => 'https://images.unsplash.com/photo-1572536147248-ac59a8abfa4b?w=600&q=80&fit=crop',
        'airpod'       => 'https://images.unsplash.com/photo-1572536147248-ac59a8abfa4b?w=600&q=80&fit=crop',
        'speaker'      => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600&q=80&fit=crop',
        'microphone'   => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?w=600&q=80&fit=crop',

        // Phones & Tablets
        'phone'        => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&q=80&fit=crop',
        'smartphone'   => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&q=80&fit=crop',
        'iphone'       => 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=600&q=80&fit=crop',
        'android'      => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&q=80&fit=crop',
        'tablet'       => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600&q=80&fit=crop',
        'ipad'         => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600&q=80&fit=crop',

        // Storage & Memory
        'pendrive'     => 'https://images.unsplash.com/photo-1618609377864-68609b857e90?w=600&q=80&fit=crop',
        'usb'          => 'https://images.unsplash.com/photo-1618609377864-68609b857e90?w=600&q=80&fit=crop',
        'flash drive'  => 'https://images.unsplash.com/photo-1618609377864-68609b857e90?w=600&q=80&fit=crop',
        'ssd'          => 'https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=600&q=80&fit=crop',
        'hdd'          => 'https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=600&q=80&fit=crop',
        'hard disk'    => 'https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=600&q=80&fit=crop',
        'hard drive'   => 'https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=600&q=80&fit=crop',
        'ram'          => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=600&q=80&fit=crop',
        'memory'       => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=600&q=80&fit=crop',
        'sd card'      => 'https://images.unsplash.com/photo-1618609377864-68609b857e90?w=600&q=80&fit=crop',

        // Printing
        'printer'      => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=600&q=80&fit=crop',
        'scanner'      => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=600&q=80&fit=crop',
        'ink'          => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=600&q=80&fit=crop',

        // Networking
        'router'       => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?w=600&q=80&fit=crop',
        'wifi'         => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?w=600&q=80&fit=crop',
        'modem'        => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?w=600&q=80&fit=crop',
        'switch'       => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&q=80&fit=crop',
        'hub'          => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&q=80&fit=crop',
        'ethernet'     => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&q=80&fit=crop',

        // Camera & Visual
        'webcam'       => 'https://images.unsplash.com/photo-1587826080692-f439cd0b70da?w=600&q=80&fit=crop',
        'camera'       => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600&q=80&fit=crop',
        'dslr'         => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600&q=80&fit=crop',
        'lens'         => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=600&q=80&fit=crop',
        'gopro'        => 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?w=600&q=80&fit=crop',

        // Power & Charging
        'charger'      => 'https://images.unsplash.com/photo-1601524909162-ae8725290836?w=600&q=80&fit=crop',
        'power bank'   => 'https://images.unsplash.com/photo-1601524909162-ae8725290836?w=600&q=80&fit=crop',
        'battery'      => 'https://images.unsplash.com/photo-1601524909162-ae8725290836?w=600&q=80&fit=crop',
        'ups'          => 'https://images.unsplash.com/photo-1601524909162-ae8725290836?w=600&q=80&fit=crop',
        'adapter'      => 'https://images.unsplash.com/photo-1601524909162-ae8725290836?w=600&q=80&fit=crop',

        // Smart & Wearables
        'smartwatch'   => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80&fit=crop',
        'watch'        => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80&fit=crop',
        'fitbit'       => 'https://images.unsplash.com/photo-1575311373937-040b8e1fd6b0?w=600&q=80&fit=crop',
        'band'         => 'https://images.unsplash.com/photo-1575311373937-040b8e1fd6b0?w=600&q=80&fit=crop',

        // Gaming
        'gaming'       => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=600&q=80&fit=crop',
        'console'      => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=600&q=80&fit=crop',
        'playstation'  => 'https://images.unsplash.com/photo-1607853202273-797f1c22a38e?w=600&q=80&fit=crop',
        'xbox'         => 'https://images.unsplash.com/photo-1612036782180-6f0b6cd846fe?w=600&q=80&fit=crop',
        'gpu'          => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=600&q=80&fit=crop',
        'graphics card'=> 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=600&q=80&fit=crop',

        // Accessories & Misc
        'cable'        => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&q=80&fit=crop',
        'stand'        => 'https://images.unsplash.com/photo-1593640408182-31c228b29f36?w=600&q=80&fit=crop',
        'dock'         => 'https://images.unsplash.com/photo-1593640408182-31c228b29f36?w=600&q=80&fit=crop',
        'bag'          => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80&fit=crop',
        'case'         => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80&fit=crop',
    ];

    /**
     * Default fallback image (generic tech product).
     */
    protected static string $defaultImage =
        'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&q=80&fit=crop';

    /**
     * Resolve the best display image URL for a product.
     *
     * Priority order:
     *  1. Admin-supplied image_url stored in DB
     *  2. Keyword match from product_name
     *  3. Static default fallback
     */
    public static function resolve(string $productName, ?string $storedUrl = null): string
    {
        // Use admin-supplied URL if set
        if ($storedUrl && filter_var($storedUrl, FILTER_VALIDATE_URL)) {
            return $storedUrl;
        }

        $lower = strtolower($productName);

        foreach (static::$keywordMap as $keyword => $url) {
            if (str_contains($lower, $keyword)) {
                return $url;
            }
        }

        return static::$defaultImage;
    }
}
