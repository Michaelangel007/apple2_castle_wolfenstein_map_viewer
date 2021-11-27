// AppleWin Debugger Script
// by Michaelangel007
// for Castle Wolfenstein

// Hide all zero page variables
    SYMMAIN  OFF
    SYMBASIC OFF

    SYMUSER  CLEAR

// 6502 Stack
    SYM FuncRet     = 103 // Get return low address from hardware stack

// ROM Vectors
    DA vRESET         3F2
    DB xRESET         3F4 // [ $3F3 ] ^ #$A5
    DA xIRQ           3FE

// IO usage
    SYM IO.KEY     = C000
    SYM IO.STROBE  = C010

// Video modes
    SYM IO.GRAPHICS = C050 // TXTCLR
    SYM IO.TEXT     = C051 // TXTSET
    SYM IO.FULL     = C052 // MIXCLR
    SYM IO.MIXED    = C053 // MIXSET
    SYM IO.PAGE1    = C054 // LOWSCR
    SYM IO.PAGE2    = C055 // HISCR
    SYM IO.LORES    = C056 // LORES
    SYM IO.HIRES    = C057 // HIRES

// Joystick
    SYM IO.BUTTON1 = C061
    SYM IO.BUTTON2 = C062
    SYM IO.BUTTON3 = C063
    SYM IO.JOY1X   = C064
    SYM IO.JOY1Y   = C065
    SYM IO.JOY2X   = C066
    SYM IO.JOY2Y   = C067
    SYM IO.JOYREAD = C070 // LDA then read JOY1X..JOY2Y

// ROM usage
    SYM BASIC.COLD = E000
    SYM ROM.BACK   = FD6F // Unnamed ROM Entry point, BCKSPC-2
    SYM ROM.COUT   = FDED
    SYM ROM.COUT2  = FDF0
    SYM ROM.CR     = FD8E
    SYM ROM.HOME   = FC58
    SYM ROM.KEYIN  = FD1B
    SYM ROM.PREAD  = FB1E
    SYM ROM.RDKEY2 = FD0C 
    SYM ROM.TEXT   = FB39
    SYM ROM.VTAB   = FC22
    SYM ROM.WAIT   = FCA8

echo "=== Castle Wolfenstein ==="
// Disk   "Castle Wolfenstein (4am crack).dsk"
// BLOAD  "@INIT"
// Start  $0880  // AA72:80 08
// Length $12BE  // AA60:BE 12
// End    $1B3D

// Prefix for names
//   z  Zero Page variables
//   g  Global variables (not on page zero)
//   _  Function (not named yet)
//   B  Bytes (not named yet)
//   T  Text string

// Vars Zero-Page
    DB zRTSLo          10
    DB zRTSHi          11

    DB ROM.zWinL       20 // Left   $00
    DB ROM.zWinW       21 // Width  $28 = 40
    DB ROM.zWinT       22 // Top    $00
    DB ROM.zWinB       23 // Bottom $18 = 24
    DB ROM.zHTAB       24 // CH Cursor X
    DB ROM.zVTAB       25 // CV Cursor Y

    DA ROM.Input       36 // CSWL
    DA ROM.Output      38 // KSWL

    // ROM.z
    DB zRNDL           4B // @
    DB zRNDH           4C

    DB GlyphOldX       60
    DB GlyphX          61
    DB GlyphY          62
    DA GlyphDst        63 // 16-bit pointer

    DB z_76            76 // @08F2= 76:7F
    DB z_D9            D9 // @08EC= D9:FF

// NOTE: CW uses interleaved CODE + DATA
// Const Data - Strings
    ASC  88E:897 // "NOMON C,O",00

    ASC  8A1:8A6 // "MON I",00

    ASC  8F7:907 // "BLOAD PIX,A$2000",00 

    ASC  914:91F // "BLOAD ^CHARSET,A$8400"
    ASC  920:929

    ASC  935:93F // "PRESS RETURN TO BEGIN.",00
    ASC  940:94B

ASC  989:98F
ASC  990:99F
ASC  9A0:9AF
ASC  9B0:9BF
ASC  9C0:9CF
ASC  9D0:9DF
ASC  9E0:9EF
ASC  9F0:9FA

ASC  A08:A17
ASC  A18:A27
ASC  A28:A37
ASC  A38:A47
ASC  A48:A49
ASC  ABE:AC8 

    ASC  AD2:ADF // "BLOAD SEKTOR,A$4004",00
    ASC  AE0:AE5

    ASC  B0A:B0F // "BSAVE CASTLE,A$4004,L$3DFC",00
    ASC  B10:B1F
    ASC  B20:B24

    ASC  B34:B3F // "BSAVE BACKUP,A$4004,L$3DFC",00
    ASC  B40:B4E

ASC  B5E:B69
ASC  B70:B75
ASC  B7C:B86
ASC  B99:BA3

ASC  BAD:BAF // "BLOAD BACKUP,A$4004",00
ASC  BB0:BBF
ASC  BC0:BC0

ASC  BD3:BE2
ASC  BE3:BED
ASC  C0B:C1A
ASC  C1B:C2A
ASC  C36:C45
ASC  C46:C4C
ASC  C6C:C76

    ASC  CA1:CA4 // "   ",00 ; SPC(3)

ASC  CE1:CF0
ASC  CF1:D00
ASC  D01:D10
ASC  D11:D20
ASC  D21:D30
ASC  D31:D40
ASC  D41:D50
ASC  D51:D60
ASC  D61:D70
ASC  D71:D80
ASC  D81:D90
ASC  D91:DA0
ASC  DA1:DB0
ASC  DB1:DC0
ASC  DC1:DC7
ASC  E36:E40

    ASC  E7A:E80 // "CASTLE",00 // XREF @ $0955
    DW   E81:E88

ASC  E89:E90 // $10 PRIVATE
ASC  E91:E99 // $20 CORPORAL
ASC  E9A:EA2 // $30 SERGEANT
ASC  EA3:EAD // $40 LIEUTENANT
ASC  EAE:EB5 // $50 CAPTAIN
ASC  EB6:EBD // $60 COLONEL
ASC  EBE:EC5 // $70 GENERAL
ASC  EC6:ED3 // $80 FIELD MARSHAL
ASC  EF9:F08
ASC  F09:F10
ASC  F1A:F1F
ASC  F20:F2F
ASC  F30:F35
ASC  F41:F4F
ASC  F50:F5F
ASC  F60:F6F
ASC  F70:F77
ASC  F83:F8F
ASC  F90:F9F

ASC  FA9:FAF // " HAND.",8D
ASC  FB0:FBF // 8D,8D,8D,"PRESS THE SPA"
ASC  FC0:FCF // "CE BAR TO KEEP",8D,8D
ASC  FD0:FDF // "THE CONTROLS AS "
ASC  FE0:FE9 // "THEY ARE.",00

ASC 1015:101F
ASC 1020:102F
ASC 1030:103F
ASC 1040:104E
ASC 1068:106F
ASC 1070:107C
ASC 109B:109F
ASC 10A0:10AF
ASC 10B0:10BF
ASC 10C0:10CF
ASC 10D0:10D4

ASC 10F2:10FF // "MOVE YOUR JOYSTICK",8D,8D
ASC 1100:110F // "TO THE UPPPER LEFT,",8D,8D
ASC 1110:111F // "HOLD IT THERE AND",8D,8D"
ASC 1120:112F // "PRESS THE SAPCE BAR.",00
ASC 1130:113F //
ASC 1140:1141 //

//ASC 1178:11C8
ASC 1178:117F // "MOVE YOUR JOYSTICK",8D,8D
ASC 1180:118F // "TO THE UPPER RIGHT,",8D,8D
ASC 1190:119F // "HOLD IT THERE AND",8D,8D
ASC 11A0:11AF // "PRESS THE SPACE BAR.",00
ASC 11B0:11BF
ASC 11C0:11C8

    ASC 1201:120F // "TURN YOUR JOYSTICK 90 DEGREES (ONE",8D,8D
    ASC 1210:121F // "QUARTER TURN), PRESS THE SPACE BAR",8D,8D
    ASC 1220:122F // "AND TRY AGAIN.",00
    ASC 1230:123F //
    ASC 1240:124F //
    ASC 1250:1257 //

    ASC 127C:127F // "ADJUSTMENT DONE!",00
    ASC 1280:128C

    ASC 129A:129F // "RIGHT",00

    ASC 12A4:12A8 // "LEFT",00

ASC 1803:180F // "WRITING DISK FILE - DO NOT RESET",00
ASC 1810:181F
ASC 1820:1823

ASC 1832:183F // ASC "                               ",00 
ASC 1840:184F // SPC(31)
ASC 1850:1851

// Data
    DB gWolf0  348 // "W" XREF @ $08AA,08CB
    DB gWolf1  349 // "O" XREF @ $08B1,08D0
    DB gWolf2  34A // "L" XREF @ $08B8,08D5
    DB gWolf3  34B // "F" XREF @ $08C2,08D8

    // DB B_034C 34C // Not used???

    DB 34D // XREF @ 08DF: STA '0' #$30 
    DB 34E // XREF @ 08E2: STA '0' #$C0
    DB 34F // XREF @ 08E7: STA '`' #$60

    DB 350 // XREF @ 1879
    DB 353 // XREF @ 187E

    DW  930 // 0F 11 -> Cursor Y, X

    DA  958 // pointer to $0E7A: "CASTLE" 

    DW  984
    DW  A03

    // 76543210
    // 11000000 $C0 Reversed 
    DB  gControsl A9E

    DW  C06
    DW  C31
    DW  C64
    DW  CDC // VHTAB

    DB  gRank E5D // $10 = Private

    DW  EF4 // VHTAB 05 00
    DW 1010 // VHTAB 04 00
    DW 1096 // VHTAB
    DW 1173 // VHTAB
    DW 11FC // VHTAB
    DW 1277 // VHTAB
    DW 17FA // VTHAB
    DW 182D // VTHAB
    DW 1855 // VTHAB

    DW COLORS 13EC:13FB // HGR Even and Odd Bytes for Colors
    // 13EC: [0=000] 00 00 Black
    // 13EE: [1=001] 55 2A Magenta
    // 13F0: [2=010] 2A 55 Green
    // 13F2: [3=011] 3F 3F White
    // 13F4: [4=100] 80 00 Black
    // 13F6: [5=101] D5 2A
    // 13F8: [6=110] AA 55
    // 13FA: [7=111] 7F 3F

    DB 185B      // ???

    DB 1F00:1FA0 // XREF @A95

    DB 1F09 // ??? @E66 STA
    DB 1F0A // ??? @E6E STA


SYM DOS.RESET  = 9DBF // Pronto-DOS

// --- Start $0880 ---
    SYM INIT.MAIN     =  880
    SYM GAME.INIT     =  8C9 // Sets 348: "WOLF" 38D", 34D:00,00,00, D9:FF, 76:7F
    SYM GAME.CONT     =  94F

    SYM SelectKEYS    =  A73
    SYM SelectJOY     =  A89
    SYM SelectPAD     =  A7E

    SYM WAIT.RET      =  C4D // Wait for RETURN to be pressed
    DB                   CBD // @CB7 STA #$3C, @CC6 DEC
    SYM NewGame       = 0CCC

    SYM SelectNewMap  =  DDF // Ctrl-C
    SYM SelectPrivate =  DDA // Ctrl-R

    SYM PrintRank     = 0E29 // @ gRank*16 = E5D, aRanks = E89
    SYM _RankChar     =  E50
    SYM _RankFindEOL  =  E47
    SYM _RankExit     =  E79
    SYN aRanks        =  E89  // Array of C Strings

    SYM RET.STACK     = 12BC // Adjust PC to skip inline data
    SYM GET.PC        = 12AA // Caller -> $10,$11

    SYM CALLDOS       = 12CF
    DB  DosLen          12E0 // Self-Modifying Code
    SYM Next.DOS      = 12DC

    SYM Print         = 12EB // Print to Text Screen
    SYM _PrintNext    = 12F3
    DB  _PrintLen       12F7 // Self-Modifying Code

    SYM PutStrRet     = 1302 // XREF @ none
    SYM _PutStrRet    = 130E // Looks to be unused???
    DB  _PutStrLen      1312
    SYM _PutStrRet2   = 1318

    SYM PutStr        = 131E // Print string to HGR SCreen
    SYM _PutNext      = 1326
    DB  _PutLen         132A

    SYM SET.HGR       = 1335
    SYM SET.TEXT      = 133F
    SYM SET.VHTAB     = 1349
    SYM CursorYX      = 1353 // inline data -> Cursor Y, X

    SYM DATA.AY       = 135F // Get inline data -> A,Y; X = stack
    SYM _RetAdd1      = 1377 // Return address++
    SYM _RetPage      = 137F // Same Page

    SYM PrintSpaces   = 1380 // XREF @ none -- Looks to be unused???
    DB  _SpcRows        139E // Usage:  JSR PrintSpaces
    DB  _SpcCols        138A //         DB  Rows, Cols

    SYM GetColors     = 13FE // X=color, out: 13FC, 13FD
    DB  ColorEven       13FC
    DB  ColorOdd        13FD

    SYM PutChar       = 1435 // Print glyph to HGR screen
    Sym _PutChar      = 1476
    SYm _ExitChar     = 1495
    DB  GlyphSaveY      1496
    DB  GlyphSaveX      1498
    Sym LineWrap      = 149A // X Cursor > 40 chars? Yes

    SYM UsePad        = 1A4D // XREF @0A7E
    SYM UseKey        = 1A9E // XREF @0A73 SelectKeys
    SYM UseJoy        = 19E1 // XREF @0A89

// Unknown functions

    SYM __0977__ = 0977 // ???
    SYM __0C2E__ = 0C2E // ???
    SYM __0C61__ = 0C61 // ???
    SYM __0CB2__ = 0CB2 // ???
    SYM __0CBC__ = 0CBC // ???
    SYM __0CC1__ = 0CC1 // ???
    SYM __0DEB__ = 0DEB // ???
    SYM __0E5C__ = 0E5C // ???
    SYM __0EF1__ = 0EF1 // ???
    SYM __100D__ = 100D // ???
    SYM __10EF__ = 10EF // ???
    SYM __1269__ = 1269 // ???
    SYM __1293__ = 1293 // ???
    SYM __13A2__ = 13A2 // ???
    SYM __13AA__ = 13AA // ???
    SYM __13CB__ = 13CB // ???
    SYM __141D__ = 141D // ???
    SYM __1421__ = 1421 // ???
    SYM __1429__ = 1429 // ???
    SYM __142D__ = 142D // ???
    SYM __14C8__ = 14C8 // ???
    SYM __14DB__ = 14DB // ??? 
    SYM __14F0__ = 14F0 // ??? Calls ROM $FD6F
    SYM __1591__ = 1591 // ???
    SYM __15A8__ = 15A8 // ???
    SYM __15AE__ = 15AE // ???
    SYM __1626__ = 1626 // ???
    SYM __16B1__ = 16B1 // ???
    SYM __16F5__ = 16F5 // ???
    SYM __174C__ = 174C // ???
    SYM __17F7__ = 17F7 // ???
    SYM __182A__ = 182A // ???
    SYM __185D__ = 185D // ??? XREF @ none
    SYM __1864__ = 1864 // ???
    SYM __18A0__ = 18A0 // ??? XREF @ $0955, inline 2 bytes
    SYM __196F__ = 196F // ???
    SYM __1976__ = 1976 // ???
    SYM __19AD__ = 19AD // ???

// --- End 1B3D ---

    DB 406A // ??? @ $0DEB 
    DB 406B // ??? @ $0DF1
    DB 406C // ??? @ $0E10
    DB 406D // ??? @ $0DFE dec
    DB 406F // ??? @ $0965

    DB FONT 8400:87FF
