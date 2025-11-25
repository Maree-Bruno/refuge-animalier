<svg xmlns="http://www.w3.org/2000/svg" width="633" height="542" viewBox="0 0 633 542"
     fill="none" {{$attributes->class('absolute inset-0 -z-10 w-full h-full min-w-full')}}>
    <defs>
        <filter id="glassAdvanced" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"/>
            <feColorMatrix in="blur" type="saturate" values="1.3" result="saturate"/>
            <feComponentTransfer in="saturate" result="bright">
                <feFuncR type="linear" slope="1.1" intercept="0.05"/>
                <feFuncG type="linear" slope="1.1" intercept="0.05"/>
                <feFuncB type="linear" slope="1.1" intercept="0.05"/>
                <feFuncA type="linear" slope="0.8"/>
            </feComponentTransfer>
        </filter>
    </defs>
    <path
        d="M200.636 205.111C157.475 88.6809 168.316 69.5787 115.318 40.0164C62.3214 10.4541 16.8722 27.5546 2.90024 168.727C-14.5647 345.191 47.0646 528.478 181.966 372.935C316.868 217.391 331.523 231.49 410.416 431.605C489.309 631.719 569.809 518.473 609.356 414.777C648.903 311.081 640.873 121.427 569.809 40.0164C498.745 -41.3938 445.011 16.6697 410.416 79.1298C368.861 120.214 243.796 321.541 200.636 205.111Z"
        fill="#FFD66B" fill-opacity="0.3" filter="url(#glassAdvanced)"/>
</svg>
