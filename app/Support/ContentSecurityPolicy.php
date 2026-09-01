<?php

namespace App\Support;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class ContentSecurityPolicy implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy->add(Directive::DEFAULT , [Keyword::SELF]);


        $scriptSources = [
            Keyword::SELF,
            'https://cdnjs.cloudflare.com',
            'https://platform.twitter.com',
            'https://cdn.ckeditor.com',
            'https://cdn.jsdelivr.net',
            'https://cdn.datatables.net',
            'https://unpkg.com',
        ];


        if (app()->environment('local')) {
            $policy->add(Directive::SCRIPT, array_merge(
                $scriptSources,
                [
                    Keyword::UNSAFE_INLINE,
                    Keyword::UNSAFE_EVAL,
                ]
            ));

        } else {
            $policy->add(Directive::SCRIPT, $scriptSources)
                ->addNonce(Directive::SCRIPT);

        }



        $policy->add(Directive::STYLE_ELEM, [
            Keyword::SELF,
            'https://fonts.googleapis.com',
            'https://cdnjs.cloudflare.com',
            'https://cdn.datatables.net',
        ])->addNonce(Directive::STYLE_ELEM);

        // Allow ONLY style attributes (needed by SweetAlert2)
        $policy->add(Directive::STYLE_ATTR, Keyword::UNSAFE_INLINE);

        $policy->add(Directive::FONT, [
            Keyword::SELF,
            'https://fonts.gstatic.com',
        ]);

        $policy->add(Directive::IMG, [Keyword::SELF, 'data:', 'https:', 'http:']);

        $policy->add(Directive::CONNECT, [
            Keyword::SELF,
            'https://cdn.ckeditor.com',
            'https://cdn.jsdelivr.net',
            'https://cdn.datatables.net',
            'https:',
            'http:',
        ]);

        $policy->add(Directive::FRAME, [
            'https://www.youtube.com',
            'https://www.youtube-nocookie.com',
            'https://platform.twitter.com',
        ]);

        $policy->add(Directive::OBJECT, Keyword::NONE);
        $policy->add(Directive::BASE, Keyword::SELF);
        $policy->add(Directive::FORM_ACTION, Keyword::SELF);
        $policy->add(Directive::FRAME_ANCESTORS, Keyword::NONE);

        // Optional strict additions
        $policy->add(Directive::WORKER, [Keyword::SELF]);
        $policy->add(Directive::MEDIA, [Keyword::SELF]);
    }
}
