/* http://en.community.shopware.com/Resetting-database-remove-testdata_detail_1666.html */

SET foreign_key_checks = 0;

TRUNCATE `s_addon_premiums`;
TRUNCATE `s_articles`;
TRUNCATE `s_articles_also_bought_ro`;
TRUNCATE `s_articles_attributes`;
TRUNCATE `s_articles_avoid_customergroups`;
TRUNCATE `s_articles_categories`;
TRUNCATE `s_articles_categories_ro`;
TRUNCATE `s_articles_categories_seo`;
TRUNCATE `s_articles_details`;
TRUNCATE `s_articles_downloads`;
TRUNCATE `s_articles_downloads_attributes`;
TRUNCATE `s_articles_esd`;
TRUNCATE `s_articles_esd_attributes`;
TRUNCATE `s_articles_esd_serials`;
TRUNCATE `s_articles_img`;
TRUNCATE `s_articles_img_attributes`;
TRUNCATE `s_articles_information`;
TRUNCATE `s_articles_information_attributes`;
TRUNCATE `s_articles_notification`;
TRUNCATE `s_articles_prices`;
TRUNCATE `s_articles_prices_attributes`;
TRUNCATE `s_articles_relationships`;
TRUNCATE `s_articles_similar`;
TRUNCATE `s_articles_similar_shown_ro`;
TRUNCATE `s_articles_supplier`;
TRUNCATE `s_articles_supplier_attributes`;
TRUNCATE `s_articles_top_seller_ro`;
TRUNCATE `s_articles_translations`;
TRUNCATE `s_articles_vote`;
TRUNCATE `s_article_configurator_dependencies`;
TRUNCATE `s_article_configurator_groups`;
TRUNCATE `s_article_configurator_options`;
TRUNCATE `s_article_configurator_option_relations`;
TRUNCATE `s_article_configurator_price_variations`;
TRUNCATE `s_article_configurator_sets`;
TRUNCATE `s_article_configurator_set_group_relations`;
TRUNCATE `s_article_configurator_set_option_relations`;
TRUNCATE `s_article_configurator_templates`;
TRUNCATE `s_article_configurator_templates_attributes`;
TRUNCATE `s_article_configurator_template_prices`;
TRUNCATE `s_article_configurator_template_prices_attributes`;
TRUNCATE `s_article_img_mappings`;
TRUNCATE `s_article_img_mapping_rules`;
TRUNCATE `s_filter_articles`;

SET foreign_key_checks = 1;

SET foreign_key_checks = 0;

TRUNCATE `s_categories`;
TRUNCATE `s_categories_attributes`;
TRUNCATE `s_categories_avoid_customergroups`;
TRUNCATE `s_emotion`;
TRUNCATE `s_emotion_attributes`;
TRUNCATE `s_emotion_categories`;
TRUNCATE `s_emotion_element`;
TRUNCATE `s_emotion_element_value`;

INSERT INTO `s_categories` (`id`, `parent`, `path`, `description`, `position`, `left`, `right`, `level`, `added`, `changed`, `metakeywords`, `metadescription`, `cmsheadline`, `cmstext`, `template`, `active`, `blog`, `external`, `hidefilter`, `hidetop`, `mediaID`, `product_box_layout`, `meta_title`, `stream_id`) VALUES
(1, NULL, NULL, 'Root', 0, 0, 0, 0, '2012-07-30 15:24:59', '2012-07-30 15:24:59', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 0, 0, NULL, NULL, NULL, NULL),
(3330001, 1, NULL, 'Deutsch', 0, 0, 0, 0, '2012-07-30 15:24:59', '2012-07-30 15:24:59', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 0, 0, NULL, NULL, NULL, NULL),
(3330002, 1, NULL, 'English', 0, 0, 0, 0, '2012-07-30 15:24:59', '2012-07-30 15:24:59', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 0, 0, NULL, NULL, NULL, NULL),
(3330003, 1, NULL, 'Französisch', 0, 0, 0, 0, '2012-07-30 15:24:59', '2012-07-30 15:24:59', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 0, 0, NULL, NULL, NULL, NULL),
(3330004, 1, NULL, 'Spanisch', 0, 0, 0, 0, '2012-07-30 15:24:59', '2012-07-30 15:24:59', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 0, 0, NULL, NULL, NULL, NULL),
(3330005, 1, NULL, 'Italienisch', 0, 0, 0, 0, '2012-07-30 15:24:59', '2012-07-30 15:24:59', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 0, 0, NULL, NULL, NULL, NULL);

INSERT INTO `s_categories_attributes` (`id`, `categoryID`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`, `attribute6`) VALUES
(1, 3330001, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3330002, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3330003, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 3330004, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 3330005, NULL, NULL, NULL, NULL, NULL, NULL);

SET foreign_key_checks = 1;