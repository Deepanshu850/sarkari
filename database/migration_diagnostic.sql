USE sarkari;

-- Store diagnostic questionnaire answers for hyper-personalization
ALTER TABLE blueprints ADD COLUMN diagnostic_json JSON DEFAULT NULL AFTER weak_subjects;
