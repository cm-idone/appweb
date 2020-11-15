<?php

header('Content-Type: text/xml');

$https = 'https://id.one-consultores.com';

$xml =
'<?xml version="1.0" encoding="iso-8859-1"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>' . $https . '/</loc>
        <changefreq>yearly</changefreq>
        <priority>1.00</priority>
    </url>
</urlset>';

echo $xml;
