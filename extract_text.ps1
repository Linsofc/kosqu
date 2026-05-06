$content = Get-Content 'temp_docx/word/document.xml' -Raw
$text = $content -replace '<[^>]+>', ' '
$text = $text -replace '\s+', ' '
$text | Out-File 'skpl_content.txt'
