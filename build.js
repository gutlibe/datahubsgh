const esbuild = require('esbuild');
const fs = require('fs');
const path = require('path');
const JavaScriptObfuscator = require('javascript-obfuscator');
const crypto = require('crypto');

const args = process.argv.slice(2);
const isWatch = args.includes('--watch');
const isMinify = args.includes('--minify');

const jsEntries = [
    'public/js/main.js',
    'public/js/home.js',
    'public/js/auth/login.js',
    'public/js/auth/register.js',
    'public/js/admin/service-status.js',
    'public/js/networks/at.js',
    'public/js/networks/mtn.js',
    'public/js/networks/telecel.js',
    'public/js/components/purchase-panel-guest.js'
];

const manifest = {};

function cleanAssets() {
    const assetsDir = path.join(__dirname, 'public', 'assets');
    if (fs.existsSync(assetsDir)) {
        fs.rmSync(assetsDir, { recursive: true, force: true });
    }
    fs.mkdirSync(assetsDir, { recursive: true });
    console.log('Cleaned public/assets');
}

function getHash(content) {
    return crypto.createHash('md5').update(content).digest('hex').substring(0, 8);
}

function addToManifest(originalPath, assetPath) {
    // Normalize paths to use forward slashes for consistent replacement
    const normOriginal = originalPath.replace(/\\/g, '/');
    const normAsset = assetPath.replace(/\\/g, '/');

    // Normalize key: /js/main.js
    const key = '/' + normOriginal.replace('public/', '');
    // Value: /assets/hash.js
    const value = '/' + normAsset.replace('public/', '');
    manifest[key] = value;
}

async function runBuild() {
    if (!isWatch) {
        cleanAssets();
    }

    const assetsDir = 'public/assets';

    // 1. Process JS Files
    for (const entry of jsEntries) {
        if (!fs.existsSync(entry)) continue;

        const fileName = path.basename(entry);
        
        const buildOptions = {
            entryPoints: [entry],
            bundle: false,
            minify: isMinify,
            write: false,
            target: ['es2015'],
        };

        if (isWatch) {
            const ctx = await esbuild.context({
                ...buildOptions,
                write: true,
                outdir: assetsDir,
            });
            await ctx.watch();
            addToManifest(entry, path.join(assetsDir, fileName));
        } else {
            const result = await esbuild.build(buildOptions);
            let code = result.outputFiles[0].text;

            if (isMinify) {
                code = JavaScriptObfuscator.obfuscate(code, {
                    compact: true,
                    controlFlowFlattening: true,
                    controlFlowFlatteningThreshold: 1,
                    numbersToExpressions: true,
                    simplify: true,
                    stringArrayShuffle: true,
                    splitStrings: true,
                    stringArrayThreshold: 1
                }).getObfuscatedCode();
            }

            const hash = getHash(code);
            const hashedName = isMinify ? `${hash}.js` : fileName;
            const outPath = path.join(assetsDir, hashedName);
            
            fs.writeFileSync(outPath, code);
            
            addToManifest(entry, outPath);
            console.log(`Processed JS: ${entry} -> ${outPath}`);
        }
    }

    // 2. Process CSS (app.css)
    const cssEntry = 'public/css/app.css';
    if (fs.existsSync(cssEntry) && !isWatch) {
        const cssCode = fs.readFileSync(cssEntry, 'utf8');
        const hash = getHash(cssCode);
        const hashedName = isMinify ? `${hash}.css` : 'app.css';
        const outPath = path.join(assetsDir, hashedName);
        
        fs.writeFileSync(outPath, cssCode);
        
        addToManifest(cssEntry, outPath);
        console.log(`Processed CSS: ${cssEntry} -> ${outPath}`);
    }

    if (!isWatch) {
        fs.writeFileSync('public/mix-manifest.json', JSON.stringify(manifest, null, 4));
        console.log('Manifest generated at public/mix-manifest.json');
    } else {
        console.log('Watching JS for changes...');
    }
}

runBuild().catch((e) => {
    console.error(e);
    process.exit(1);
});