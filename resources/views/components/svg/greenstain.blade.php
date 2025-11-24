<svg xmlns="http://www.w3.org/2000/svg" width="1440" height="1335" viewBox="0 0 1440 1335"
     fill="none"{{$attributes->class('absolute inset-0 -z-10 w-full h-full min-w-full')}}>
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
    <path d="M1111.46 588.177C1314.56 642.305 1366.55 647.332 1538.44 593.485C1710.34 539.639 1932.2 231.086 1907.99 77.1989C1877.72 -115.16 1463.11 73.87 1089.57 402.496C716.041 731.123 659.875 737.117 295.025 650.355C-69.8251 563.592 -748.622 1046.19 -692.04 1154C-622.993 1285.56 -262.871 1394.59 -20.3391 1298.19C222.192 1201.79 387.865 1094.05 472.305 988.141C590.801 893.457 908.363 534.048 1111.46 588.177Z"
          fill="#B8E0D2" fill-opacity="0.3" filter="url(#glassAdvanced)"/>
</svg>
