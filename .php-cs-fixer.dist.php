<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__,
    ])
    ->append([
        __FILE__,
    ])
    ->exclude([
        'vendor',
    ])
;

return
    (new PhpCsFixer\Config())
        ->setFinder($finder)
        ->setRiskyAllowed(true)
        ->setRules([
            '@PHP74Migration' => true,
            '@PHP74Migration:risky' => true,
            '@PHP80Migration' => true,
            '@PHP80Migration:risky' => true,
            '@PhpCsFixer' => true,
            '@PhpCsFixer:risky' => true,
            '@PHPUnit84Migration:risky' => true,
            '@PSR12' => true,
            '@PSR12:risky' => true,
            // Todo: remove when https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/5495 is resolved.
            'binary_operator_spaces' => ['operators' => ['|' => null]],
            'blank_line_before_statement' => [
                'statements' => [
                    'case',
                    'continue',
                    'declare',
                    'default',
                    'return',
                    'throw',
                    'try',
                ],
            ],
            'braces' => [
                'allow_single_line_anonymous_class_with_empty_body' => true,
                'allow_single_line_closure' => true,
            ],
            'comment_to_phpdoc' => ['ignored_tags' => ['fixme']],
            'date_time_immutable' => true,
            'final_class' => true,
            'final_public_method_for_abstract_class' => true,
            'fopen_flags' => ['b_mode' => true],
            'no_superfluous_phpdoc_tags' => ['allow_mixed' => false, 'allow_unused_params' => true, 'remove_inheritdoc' => true],
            'nullable_type_declaration_for_default_null_value' => true,
            'ordered_class_elements' => [
                'order' => [
                    'use_trait',
                    'constant_public',
                    'constant_protected',
                    'constant_private',
                    'property_public_static',
                    'property_protected_static',
                    'property_private_static',
                    'property_public',
                    'property_protected',
                    'property_private',
                    'construct',
                    'destruct',
                    'phpunit',
                    'method_public_static',
                    'method_public_abstract_static',
                    'method_protected_static',
                    'method_protected_abstract_static',
                    'method_private_static',
                    'method_public',
                    'method_public_abstract',
                    'method_protected',
                    'method_protected_abstract',
                    'method_private',
                ],
            ],
            'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],
            'php_unit_strict' => false,
            'php_unit_test_case_static_method_calls' => false, // Todo: use HappyInc/php_unit_test_case_functions.
            'phpdoc_add_missing_param_annotation' => false,
            'phpdoc_align' => false,
            'phpdoc_separation' => false,
            'phpdoc_to_comment' => false,
            'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
            'return_assignment' => false,
            'single_line_comment_style' => ['comment_types' => ['hash']],
            'trailing_comma_in_multiline' => ['after_heredoc' => true, 'elements' => ['arrays', 'arguments', 'parameters']],
            'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        ])
    ;
