<?php
if (extension_loaded('gd') && extension_loaded('zip') && extension_loaded('fileinfo')) {
    echo "✅ Все необходимые расширения загружены.\n";
} else {
    echo "❌ Не все расширения доступны.\n";
}

echo "✅ PHP версия: " . phpversion() . "\n";
?>