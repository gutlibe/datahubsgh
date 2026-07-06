const JavaScriptObfuscator = require('javascript-obfuscator');
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');

const jsDir = path.join(__dirname, 'public', 'js');
const distDir = path.join(__dirname, 'public', 'dist');
const manifestPath = path.join(__dirname, 'public', 'mix-manifest.json');
let manifest = {};

// Clean dist directory before running
if (fs.existsSync(distDir)) {
    fs.rmSync(distDir, { recursive: true, force: true });
}
fs.mkdirSync(distDir, { recursive: true });

function ensureDirectoryExistence(filePath) {
    const dirname = path.dirname(filePath);
    if (fs.existsSync(dirname)) {
        return true;
    }
    ensureDirectoryExistence(dirname);
    fs.mkdirSync(dirname);
}

function obfuscateFile(filePath) {
    const sourceCode = fs.readFileSync(filePath, 'utf8');
    const obfuscationResult = JavaScriptObfuscator.obfuscate(sourceCode, {
        compact: true,
        controlFlowFlattening: true,
        controlFlowFlatteningThreshold: 1,
        numbersToExpressions: true,
        simplify: true,
        stringArrayShuffle: true,
        splitStrings: true,
        stringArrayThreshold: 1
    });

    const obfuscatedCode = obfuscationResult.getObfuscatedCode();
    
    // Generate hash based on obfuscated content
    const hash = crypto.createHash('md5').update(obfuscatedCode).digest('hex').substring(0, 8);
    
    const relativePath = path.relative(jsDir, filePath);
    const parsedPath = path.parse(relativePath);
    
    // Construct new filename with hash: hash.js (completely hashed name)
    const hashedFilename = `${hash}${parsedPath.ext}`;
    const newRelativePath = path.join(parsedPath.dir, hashedFilename);
    const newFilePath = path.join(distDir, newRelativePath);

    // Normalize paths for web use (forward slashes)
    const webOriginalPath = '/js/' + relativePath.split(path.sep).join('/');
    const webDistPath = '/dist/' + newRelativePath.split(path.sep).join('/');

    manifest[webOriginalPath] = webDistPath;

    ensureDirectoryExistence(newFilePath);
    fs.writeFileSync(newFilePath, obfuscatedCode);
    console.log(`Obfuscated: ${filePath} -> ${newFilePath}`);
}

function walkDir(dir, callback) {
    fs.readdirSync(dir).forEach(f => {
        let dirPath = path.join(dir, f);
        let isDirectory = fs.statSync(dirPath).isDirectory();
        isDirectory ?
            walkDir(dirPath, callback) :
            callback(path.join(dir, f));
    });
};

console.log('Starting obfuscation...');
walkDir(jsDir, (filePath) => {
    if (path.extname(filePath) === '.js') {
        obfuscateFile(filePath);
    }
});

fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 4));
console.log(`Manifest generated at ${manifestPath}`);
console.log('Obfuscation complete.');
