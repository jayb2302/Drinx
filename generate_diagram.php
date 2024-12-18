<?php
function scanDirectory($dir, $parent = "Root", &$output = [], $exclude = []) {
    $files = scandir($dir);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || in_array($file, $exclude)) {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $file;

        // Sanitize node names
        $node = str_replace([' ', '.', '-', '/'], '_', $file); // Replace problematic characters
        $node = preg_replace('/[^a-zA-Z0-9_]/', '', $node); // Remove special characters
        $node = ucfirst($node); // Capitalize for readability

        // Avoid reserved Mermaid.js keywords
        $reservedKeywords = ['style', 'class', 'subgraph', 'linkStyle', 'direction', 'graph'];
        if (in_array(strtolower($node), $reservedKeywords)) {
            $node .= "_Node"; // Append '_Node' to avoid conflicts
        }

        // Add the relationship to the output
        $output[] = "    $parent --> $node";

        // Recursively process subdirectories
        if (is_dir($path)) {
            scanDirectory($path, $node, $output, $exclude);
        }
    }
}

// Define the directories/files to exclude
$exclude = ['.git', 'README.md', 'logs', '.env'];

// Start generating the Mermaid.js diagram
$projectRoot = __DIR__;
$output = [
    "---",
    "config:",
    "  layout: fixed",
    "---",
    "flowchart TD",
    "    Root[\"Project Root\"]"
];

scanDirectory($projectRoot, "Root", $output, $exclude);

// Add styles for nodes
$output[] = "style Root fill:#AA00FF,stroke:#333,stroke-width:2px;";
$output[] = "classDef folderStyle fill:#FFD700,stroke:#333,stroke-width:2px;";
$output[] = "classDef fileStyle fill:#2962FF,stroke:#000,stroke-width:2px;";
$output[] = "class * folderStyle;";
$output[] = "class * fileStyle;";

// Write the Mermaid.js diagram to a file
$mermaidFile = $projectRoot . DIRECTORY_SEPARATOR . "project_structure.mmd";
file_put_contents($mermaidFile, implode(PHP_EOL, $output));

echo "Mermaid.js diagram saved to $mermaidFile\n";