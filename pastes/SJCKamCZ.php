<?php
$regex='%^
([a-zA-Z0-9_]+)
\s*\(\s*
((?:
(?:\[\s*)?
(\??(?:bool|true|false|int|integer|float|double|string|array|object|mixed|resource|null|[A-Za-z_-]+)(?:\|\??(?:bool|true|false|int|integer|float|double|string|array|object|mixed|resource|null))*)\s+
(&?(?:\.\.\.)?\$[a-zA-Z0-9_]+)\s*
(=\s*((?:true|false|-?\d+|\d+\.\d*|\.\d+|(?:ini_get\()?[\'"]?[A-Za-z0-9:.\\\\_]+[\'"]?\(?\)?(?:\s*\|\s*[A-Z0-9_]+)*|\'\'|\"\"|null|\[\]|array\s*\(\s*\)))?\s*)?
(?:\]\s*)?)?
(?:\[?,\s*
(\??(?:bool|true|false|int|integer|float|double|string|array|object|mixed|resource|null|[A-Za-z_-]+)(?:\|\??(?:bool|true|false|int|integer|float|double|string|array|object|mixed|resource|null))*)\s+
(&?(?:\.\.\.)?\$[a-zA-Z0-9_]+)\s*
(=\s*((?:true|false|-?\d+|\d+\.\d*|\.\d+|(?:ini_get\()?[\'"]?[A-Za-z0-9:.\\\\_]+[\'"]?\(?\)?(?:\s*\|\s*[A-Z0-9_]+)*|\'\'|\"\"|null|\[\]|array\s*\(\s*\)))\s*)?
(?:\]+\s*)?)*
)?
\)
(?:\s*:\s*
(\??(?:bool|true|false|int|integer|float|double|string|array|object|mixed|resource|null|[A-Za-z_-]+)(?:\|\??(?:bool|true|false|int|integer|float|double|string|array|object|mixed|resource|null))*)
)?$%x';
$regex2='(?:\[?,?\s*
(\??(?:bool|true|false|int|integer|float|double|string|array|object|mixed|resource|null|[A-Za-z_-]+)(?:\|\??(?:bool|true|false|int|integer|float|double|string|array|object|mixed|resource|null))*)\s+
(&?(?:\.\.\.)?\$[a-zA-Z0-9_]+)\s*
(=\s*((?:true|false|-?\d+|\d+\.\d*|\.\d+|(?:ini_get\()?[\'"]?[A-Za-z0-9:.\\\\_]+[\'"]?\(?\)?(?:\s*\|\s*[A-Z0-9_]+)*|\'\'|\"\"|null|\[\]|array\s*\(\s*\)))\s*)?
(?:\]+\s*?)?)%x';
$regex0='#<li><a href="function.([a-zA-Z0-9._-]+).html">([a-zA-Z0-9_-]+)</a>(?: \S+ ([^<]+)|\s*)?</li>#';
$xpath1='//div[@class="refentry"]/div[@class="refnamediv"]/p[@class="refpurpose"]';
$xpath2='//div[contains(@class, "description")]/div[contains(@class, "methodsynopsis")][span[contains(@class, "methodname")]]';
$xpath3='//div[@class="refentry"]/div[contains(@class, "description")]/p[@class="para" or @class="simpara"]/span[@class="methodname" or @class="function"]';
$xpath4='//div[span[contains(@class, "methodname")]]';
