<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'C',
            'C++',
            'C#',
            'Clojure',
            'CSS',
            'D',
            'Diff',
            'Erlang',
            'Go',
            'Graphviz',
            'Groovy',
            'Haskell',
            'HTML',
            'Java',
            'JavaScript',
            'Laravel',
            'LaTel',
            'Lisp',
            'Lua',
            'Makelife',
            'Markdown',
            'MATLAB',
            'Objective-C',
            'OCaml',
            'Pascal',
            'Perl',
            'PHP',
            'Python',
            'R',
            'Rails',
            'Regular Expression',
            'reStructuredText',
            'Ruby',
            'Rust',
            'Scala',
            'Seti_UI',
            'Shell Script',
            'SQL',
            'Vue',
            'XML'
        ];
        foreach ($tags as $tag){
            \App\Tag::create([
                'tag' => $tag
            ]);
        }
    }
}
