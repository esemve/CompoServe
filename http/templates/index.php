<!doctype html>
<html>
<head>
    <title>Composerve</title>
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
</head>
<body>

<h1>CompoServe</h1>


<div id="container">

    <?php
    $i = 0;
    if (!empty($packages['packages']))
    {
	foreach ($packages['packages'] AS $name => $versions)
	{
	    $i++;
	    if ($i>1)
	    {
		echo '<hr>';
	    }
	    echo '<h2>'.$name.'</h2>';
	    if (!empty($versions))
	    {
		$versionOutput = [];

		foreach (array_reverse($versions) AS $version => $data)
		{
		    $versionOutput[] =  '<a href="'.$data['dist']['url'].'">'.$version.'</a>';
		}
		
		if (!empty($versionOutput))
		{
		    echo 'ZipBalls: ' . implode(' / ',$versionOutput);
		}
		
	    }
	}
    }
    ?>
</div>

<div id="version">
    CompoServe | <a target="_blank" href="https://github.com/esemve/CompoServe">GitHub</a> | <a target="_blank" href="https://github.com/esemve">esemve</a> | 2016
</div>







</body>
</html>