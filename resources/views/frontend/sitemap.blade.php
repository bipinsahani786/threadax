{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    {{-- 1. Static Core Pages --}}
    @foreach($staticPages as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        <lastmod>{{ $page['lastmod'] }}</lastmod>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
    @endforeach

    {{-- 2. Categories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ $category['url'] }}</loc>
        <lastmod>{{ $category['lastmod'] }}</lastmod>
        <changefreq>{{ $category['changefreq'] }}</changefreq>
        <priority>{{ $category['priority'] }}</priority>
        @if(!empty($category['image']))
        <image:image>
            <image:loc>{{ $category['image'] }}</image:loc>
            <image:title><![CDATA[{{ $category['title'] }}]]></image:title>
        </image:image>
        @endif
    </url>
    @endforeach

    {{-- 3. Products Catalog --}}
    @foreach($products as $product)
    <url>
        <loc>{{ $product['url'] }}</loc>
        <lastmod>{{ $product['lastmod'] }}</lastmod>
        <changefreq>{{ $product['changefreq'] }}</changefreq>
        <priority>{{ $product['priority'] }}</priority>
        @if(!empty($product['image']))
        <image:image>
            <image:loc>{{ $product['image'] }}</image:loc>
            <image:title><![CDATA[{{ $product['title'] }}]]></image:title>
        </image:image>
        @endif
    </url>
    @endforeach

    {{-- 4. Blog Posts & Articles --}}
    @foreach($blogs as $blog)
    <url>
        <loc>{{ $blog['url'] }}</loc>
        <lastmod>{{ $blog['lastmod'] }}</lastmod>
        <changefreq>{{ $blog['changefreq'] }}</changefreq>
        <priority>{{ $blog['priority'] }}</priority>
        @if(!empty($blog['image']))
        <image:image>
            <image:loc>{{ $blog['image'] }}</image:loc>
            <image:title><![CDATA[{{ $blog['title'] }}]]></image:title>
        </image:image>
        @endif
    </url>
    @endforeach

    {{-- 5. CMS Static Information Pages --}}
    @foreach($cmsPages as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        <lastmod>{{ $page['lastmod'] }}</lastmod>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
    @endforeach

</urlset>
