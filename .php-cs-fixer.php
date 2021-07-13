<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('assets/*')
    ->in([
        __DIR__.'/src',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
	->setRules([
		'@PSR12' => true,
		// 'array_syntax' => ['syntax' => 'short'],
		'ordered_imports' => ['sort_algorithm' => 'alpha'],
		'no_unused_imports' => true,
		'not_operator_with_successor_space' => true,
		'trailing_comma_in_multiline' => true,
		'phpdoc_scalar' => true,
		'unary_operator_spaces' => true,
		'binary_operator_spaces' => true,
		'binary_operator_spaces' => [
			'default' => 'single_space',
			'operators' => ['=>' => 'align_single_space_minimal']
		],
		'phpdoc_single_line_var_spacing' => true,
		'phpdoc_var_without_name' => true,
		'class_attributes_separation' => [
			'elements' => [
				'method' => 'one',
			],
		],
		'indentation_type' => true,
	    'linebreak_after_opening_tag' => true,
	    'line_ending' => true,
	    'lowercase_cast' => true,
	    'lowercase_keywords' => true,
	    'lowercase_static_reference' => true, // added from Symfony
	    'magic_method_casing' => true, // added from Symfony
	    'magic_constant_casing' => true,
	    'method_argument_space' => true,
	    'native_function_casing' => true,
	    'no_alias_functions' => true,
		'no_short_bool_cast' => true,
		'no_singleline_whitespace_before_semicolons' => true,
		'method_argument_space' => [
			'on_multiline' => 'ensure_fully_multiline',
			'keep_multiple_spaces_after_comma' => true,
		],
		'single_trait_insert_per_statement' => true,
	])
    ->setLineEnding("\n")
    ->setIndent(str_repeat(' ', 4))
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setFinder($finder);
