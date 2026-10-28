const fs = require('fs');
const path = 'wp-content/themes/jobscout/page-contact.php';
let content = fs.readFileSync(path, 'utf8');

// Replace section 1: Reset khung trang
const targetResetOld = `  /* 1. Reset khung trang & Loại bỏ sidebar, breadcrumb thừa */
  body.page-template-page-contact,
  .page-template-page-contact #page,
  .page-template-page-contact #content,
  .page-template-page-contact .site-content,
  .page-template-page-contact .site-main {
    background-color: #FFFFFF !important;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  }

  .page-template-page-contact .site-content > header.entry-header,
  .page-template-page-contact .site-content > header.page-header,
  .page-template-page-contact .entry-header,
  .page-template-page-contact .entry-title,
  .page-template-page-contact .edit-link,
  .page-template-page-contact .entry-footer,
  .page-template-page-contact #secondary,
  .page-template-page-contact .breadcrumbs,
  .page-template-page-contact .breadcrumb-wrap {
    display: none !important;
  }

  .page-template-page-contact #primary {
    width: 100% !important;
    float: none !important;
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    background: transparent !important;
  }

  .page-template-page-contact .site-content .container {
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
  }

  .page-template-page-contact #main {
    padding: 0 !important;
    margin: 0 !important;
  }`;

const replaceResetNew = `  /* 1. Reset khung trang & Loại bỏ sidebar, breadcrumb thừa (Tràn viền 100% cho khung chính, sát header) */
  body.page-template-page-contact,
  body.page-template-page-contact-php,
  .page-template-page-contact #page,
  .page-template-page-contact-php #page,
  .page-template-page-contact #content,
  .page-template-page-contact-php #content,
  .page-template-page-contact .site-main,
  .page-template-page-contact-php .site-main {
    background-color: #FFFFFF !important;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  }

  .page-template-page-contact .site-content,
  .page-template-page-contact-php .site-content,
  .page-contact .site-content {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    padding: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
    background-color: #FFFFFF !important;
  }

  .page-template-page-contact .site-content > header.entry-header,
  .page-template-page-contact .site-content > header.page-header,
  .page-template-page-contact .entry-header,
  .page-template-page-contact .entry-title,
  .page-template-page-contact .edit-link,
  .page-template-page-contact .entry-footer,
  .page-template-page-contact #secondary,
  .page-template-page-contact .breadcrumbs,
  .page-template-page-contact .breadcrumb-wrap,
  .page-template-page-contact-php .site-content > header.entry-header,
  .page-template-page-contact-php .site-content > header.page-header,
  .page-template-page-contact-php .entry-header,
  .page-template-page-contact-php .entry-title,
  .page-template-page-contact-php .edit-link,
  .page-template-page-contact-php .entry-footer,
  .page-template-page-contact-php #secondary,
  .page-template-page-contact-php .breadcrumbs,
  .page-template-page-contact-php .breadcrumb-wrap {
    display: none !important;
  }

  .page-template-page-contact #primary,
  .page-template-page-contact-php #primary {
    width: 100% !important;
    float: none !important;
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    background: transparent !important;
  }

  .page-template-page-contact .site-content > .container,
  .page-template-page-contact-php .site-content > .container,
  .page-template-page-contact .site-content .container,
  .page-template-page-contact-php .site-content .container {
    max-width: 100% !important;
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  .page-template-page-contact #main,
  .page-template-page-contact-php #main {
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
  }

  .contact-page-wrapper {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #333333;
    line-height: 1.6;
    background-color: #ffffff;
    overflow-x: hidden;
    width: 100%;
  }`;

// Normalize line endings for replacement
const normalize = s => s.replace(/\r\n/g, '\n');
const isCRLF = content.includes('\r\n');

content = normalize(content);
const normTargetReset = normalize(targetResetOld);
const normReplaceReset = normalize(replaceResetNew);

if (!content.includes(normTargetReset)) {
  console.error("targetResetOld not found in content!");
  process.exit(1);
}
content = content.replace(normTargetReset, normReplaceReset);

// Next: Replace Gutenberg Hero Banner styling to match About Us min-height & responsive
const targetHeroOld = `  /* Gutenberg block styling support */
  .page-template-page-contact .contact-hero-banner {
    position: relative !important;
    width: 100% !important;
    min-height: 330px !important;
    background-color: #1a1a1a !important;
    background-position: center 38% !important;
    background-size: cover !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 40px 20px !important;
    margin: 0 !important;
  }

  .page-template-page-contact .contact-hero-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 40px !important;
    font-weight: 800 !important;
    color: #FFFFFF !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    margin: 0 !important;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.45) !important;
  }`;

const replaceHeroNew = `  /* Gutenberg block styling support: Banner tràn viền 100% giống trang Về chúng tôi */
  .contact-hero-banner.wp-block-cover,
  .page-template-page-contact .contact-hero-banner.wp-block-cover,
  .page-template-page-contact-php .contact-hero-banner.wp-block-cover,
  .page-template-page-contact .contact-hero-banner,
  .page-template-page-contact-php .contact-hero-banner,
  .page-template-page-contact .cp-hero-banner,
  .page-template-page-contact-php .cp-hero-banner {
    min-height: 480px !important;
    padding: 40px 20px !important;
    position: relative !important;
    background-position: center 45% !important;
    background-size: cover !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    box-sizing: border-box !important;
  }

  .contact-hero-title,
  .page-template-page-contact .contact-hero-title,
  .page-template-page-contact-php .contact-hero-title,
  .page-template-page-contact .cp-hero-title,
  .page-template-page-contact-php .cp-hero-title {
    color: #ffffff !important;
    font-size: clamp(28px, 4.2vw, 44px) !important;
    font-weight: 700 !important;
    letter-spacing: 2px !important;
    text-transform: uppercase !important;
    line-height: 1.35 !important;
    margin: 0 !important;
    text-shadow: 0 3px 12px rgba(0, 0, 0, 0.65) !important;
    font-family: 'Montserrat', sans-serif !important;
    text-align: center !important;
  }`;

const normTargetHero = normalize(targetHeroOld);
const normReplaceHero = normalize(replaceHeroNew);

if (!content.includes(normTargetHero)) {
  console.error("targetHeroOld not found in content!");
  process.exit(1);
}
content = content.replace(normTargetHero, normReplaceHero);

// Next: Ensure inner containers for address and columns are neatly constrained
const targetAddressOld = `  .page-template-page-contact .contact-section-address {
    background: #FFFFFF !important;
    padding: 100px 24px 60px !important;
    text-align: center !important;
    margin: 0 !important;
  }`;

const replaceAddressNew = `  .page-template-page-contact .contact-section-address,
  .page-template-page-contact-php .contact-section-address {
    background: #FFFFFF !important;
    padding: 85px 24px 60px !important;
    text-align: center !important;
    margin: 0 auto !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  .page-template-page-contact .contact-section-address .wp-block-group__inner-container,
  .page-template-page-contact-php .contact-section-address .wp-block-group__inner-container {
    max-width: 820px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    box-sizing: border-box !important;
  }`;

const normTargetAddress = normalize(targetAddressOld);
const normReplaceAddress = normalize(replaceAddressNew);

if (!content.includes(normTargetAddress)) {
  console.error("targetAddressOld not found in content!");
  process.exit(1);
}
content = content.replace(normTargetAddress, normReplaceAddress);

// Next: Section columns container
const targetColumnsOld = `  .page-template-page-contact .contact-section-columns {
    background: #F5F6F8 !important;
    padding: 65px 24px 75px !important;
    margin: 0 !important;
  }`;

const replaceColumnsNew = `  .page-template-page-contact .contact-section-columns,
  .page-template-page-contact-php .contact-section-columns {
    background: #F5F6F8 !important;
    padding: 68px 24px 78px !important;
    margin: 0 auto !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  .page-template-page-contact .contact-section-columns .wp-block-group__inner-container,
  .page-template-page-contact-php .contact-section-columns .wp-block-group__inner-container {
    max-width: 1140px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    box-sizing: border-box !important;
  }`;

const normTargetColumns = normalize(targetColumnsOld);
const normReplaceColumns = normalize(replaceColumnsNew);

if (!content.includes(normTargetColumns)) {
  console.error("targetColumnsOld not found in content!");
  process.exit(1);
}
content = content.replace(normTargetColumns, normReplaceColumns);

// Update responsive rules for hero banner min-height
const targetRespOld = `  @media (max-width: 600px) {
    .cp-hero-banner {
      min-height: 280px;
    }
    .contact-hero-title,
    .cp-hero-title {
      font-size: 26px !important;
    }`;

const replaceRespNew = `  @media (max-width: 991px) {
    .contact-hero-banner.wp-block-cover,
    .page-template-page-contact .contact-hero-banner.wp-block-cover,
    .page-template-page-contact-php .contact-hero-banner.wp-block-cover,
    .page-template-page-contact .contact-hero-banner,
    .page-template-page-contact .cp-hero-banner {
      min-height: 380px !important;
    }
  }

  @media (max-width: 600px) {
    .contact-hero-banner.wp-block-cover,
    .page-template-page-contact .contact-hero-banner.wp-block-cover,
    .page-template-page-contact-php .contact-hero-banner.wp-block-cover,
    .page-template-page-contact .contact-hero-banner,
    .page-template-page-contact .cp-hero-banner {
      min-height: 300px !important;
    }
    .contact-hero-title,
    .cp-hero-title {
      font-size: 26px !important;
    }`;

const normTargetResp = normalize(targetRespOld);
const normReplaceResp = normalize(replaceRespNew);

if (!content.includes(normTargetResp)) {
  console.error("targetRespOld not found in content!");
  process.exit(1);
}
content = content.replace(normTargetResp, normReplaceResp);

if (isCRLF) {
  content = content.replace(/\n/g, '\r\n');
}

fs.writeFileSync(path, content, 'utf8');
console.log("Successfully updated page-contact.php!");

