<?php
die();
putenv("HOME=/home/bitrix/ext_www/belwooddoors.ru");
// The commands
$commands = array(
    'echo $PWD',
   'git config --global user.email git@belwooddoors.ru',
   'git config --global user.name "belwooddoors server"',
   'git config --global --list',
	'whoami',
//'git config --global --list',
    'git add --all',
    'git commit -m "Changes on production"',
    'git pull origin master',
    'git checkout --theirs .',
    'git commit -am "Remote Conflict"',
    'git push origin master',
    'git submodule sync',
    'git submodule update',
    'git submodule status',
    'git submodule status',
    'git submodule status',
);
// Run the commands for output
$output = '';
foreach($commands AS $command){
// Run it
	$tmp = shell_exec($command." 2>&1 ");
// Output
    $output .= "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
    $output .= htmlentities(trim($tmp)) . "\n";


}

// Make it pretty for manual user access (and why not?)
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>GIT DEPLOYMENT SCRIPT</title>
</head>
<body style="background-color: #000000; color: #FFFFFF; font-weight: bold; padding: 0 10px;">
<pre>
. ____ . ____________________________
|/ \| | |
[| <span style="color: #FF0000;">&hearts; &hearts;</span> |] | Git Deployment Script v0.1 |
|___==___| / &copy; oodavid 2017 Newsite.by |
|____________________________|

    <?php echo $output; ?>
</pre>
</body>
</html>
