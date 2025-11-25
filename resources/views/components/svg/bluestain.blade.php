<svg xmlns="http://www.w3.org/2000/svg" width="2330" height="650" viewBox="-200 0 1440 542" fill="none"
    {{$attributes->class('absolute inset-0 -z-10 w-full h-full min-w-full')}}>
    <defs>
        <filter id="glassAdvanced" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur in="SourceGraphic" stdDeviation="15" result="blur"/>
            <feColorMatrix in="blur" type="saturate" values="1.3" result="saturate"/>
            <feComponentTransfer in="saturate" result="bright">
                <feFuncR type="linear" slope="1.1" intercept="0.05"/>
                <feFuncG type="linear" slope="1.1" intercept="0.05"/>
                <feFuncB type="linear" slope="1.1" intercept="0.05"/>
                <feFuncA type="linear" slope="0.8"/>
            </feComponentTransfer>
        </filter>
    </defs>
    <path d="M166.504 205.111C14.522 88.6809 52.694 69.5787 -133.926 40.0164C-320.546 10.4541 -480.587 27.5546 -529.787 168.727C-591.287 345.191 -374.27 528.478 100.763 372.935C575.796 217.391 627.4 231.49 905.209 431.605C1183.02 631.719 1466.48 518.473 1605.74 414.777C1745 311.081 1716.72 121.427 1466.48 40.0164C1216.24 -41.3938 1027.0316.6697 905.209 79.1298C758.882 120.214 318.487 321.541 166.504 205.111Z"
          fill="#2E4A62"
          fill-opacity="0.15"
          filter="url(#glassAdvanced)"/>
</svg>

