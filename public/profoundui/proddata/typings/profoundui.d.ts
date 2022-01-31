
declare function getObj(id: string) :DomElement | null;
declare function get(id:string | DomElement): string;
declare function applyProperty(id: string | DomElement, property: string, value: string): void;
declare function pressKey(key: string, additionalKeys?: string, onTimeout?: boolean): void;
declare function changeElementClass(id: string | DomElement, className: string): void;
declare function changeElementValue(id: string | DomElement, value: string): void;
declare function getElementValue(id: string | DomElement): string;
declare function getInnerText(id: string | DomElement): string;
declare function hideElement(id: string): void;
declare function hideElements(id: string, id2?: string): void;
declare function ajax(url?: string | ajaxConfig, handler?: Function): void;
declare function ajaxJSON(url?: string |ajaxConfig, handler?: Function): void;
declare function ajaxXML(url?: string| ajaxConfig, handler?: Function): void 
declare function currentDate(editCode?: number, fullYearFormat?: boolean): string;
declare function currentTime(): string;
declare function currentUser(): string;
declare function getSystemName(): string;
declare function addEvent(obj: string, eventName: string, func: Function): void;
declare function preventEvent(event: string): void;
declare function removeEvent(obj: string, eventName: string, func: Function): void;




declare namespace pui {
    declare class AjaxRequest {
        async?: boolean;
        headers?: Object;
        method?: string;
        params?: Object;
        password?: string;
        postData?: string;
        suppressAlert?: boolean;
        url: string;
        user?: string;
        public onfail(ajaxObject:AjaxRequest): void;
        public onsuccess(ajaxObject:AjaxRequest): void;
        public onready(ajaxObject:AjaxRequest): void;
        public abort(): void;
        public getAllResponseHeaders(): string;
        public getResponseHeader(header:string): string;
        public getResponseText(): string;
        public getResponseXML(): XMLDocument;
        public getStatus(): number;
        public getStatusMessage(): string;
        public getStatusText(): string;
        public ok(): boolean;
        public send(): void;
        public setRequestHeader(header: string, value: string): void;
    }
    export function set(id: string | DomElement, value: string): void;
    export function click(id: string | DomElement): void;
    export function link(url: string): void;
    export function errorTip(id: string| DomElement, message: string, hideDelay?: number): void;
    /**
     * This method dynamically adds CSS code to the currently running application.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=11206690
     * @param cssCode A string with a CSS rule.
     */
    export function addCSS(cssCode: string): void;
    /**
     * This method loads an external Cascading Style Sheet file on the fly.
     * @param path The path to the cascading style sheet file.
     */
    export function loadCSS(path: string): boolean;
    /**
     * This method dynamically loads an external JavaScript file
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=11206707
     * @param path The JavaScript path to load.
     * @param config A configuration object that specifies the JavaScript path to load along with other configuration options
     */
    export function loadJS(path?: string, config?: loadJSConfig): boolean; //this also takes config options, idrk what to do with those
    export function runPCCommand(command: string): void;
    /**
     * his API initiates a download from a file in the Integrated File System (IFS) to the browser.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=10092549
     * @param config JavaScript object containing configuration properties.
     */
    export function download(config: downloadConfig): void;
    /**
     * This API calculates the URL needed to start a download.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=13664291
     * @param config JavaScript object containing configuration properties.
     */
    export function downloadURL(config: downloadURLConfig): string;
    /**
     * This function navigates to a URL in a new browser tab or window
     * @param url The url address to navigate to.
     */
    export function openURL(url: string): void;
    export function maskScreen(): void;
    export function unmaskScreen(): void;
    /**
     * This function prevents a Profound UI Rich Display File session or a Profound UI Genie session from timing out.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=4850055
     */
    export function keepAlive(): void;
    /**
     * This API refreshes the browser page and reloads all CSS and JavaScript files
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=9601118
     * @param config JavaScript object containing configuration properties.
     */
    export function refresh(config: refreshConfig): void;
    /**
     * Startup API. This API runs an anonymous Profound UI program.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=7864464
     * @param config JavaScript object containing configuration properties.
     */
    export function run(config: runConfig): void;
    /**
     * Startup API. Starts an authenticated session with a sign on screen.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=7864470 
     * @param config JavaScript object containing configuration properties.
     */
    export function signon(config: signonConfig): void;
    /**
     * Mobile API. 
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=13336579 
     * @param message The message to display in the box.
     * @param callback The optional function to call when the dialog is dimissed
     * @param title The optional title for the native dialog box
     * @param buttonName The optional name of the button to dismiss the dialog
     */
    export function alert(message: string, callback?: Function, title?: string, buttonName?: string): void;
    /**
     * Mobile API. This function animates an element or a container using a CSS3 transition.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=7602219
     * @param config JavaScript object containing configuration properties. 
     */
    export function animate(config: animateConfig): void;
    /**
     * Mobile API. This API takes a picture using a mobile device and uploads it to the IFS on the IBM i.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=8519752
     * @param config JavaScript object containing configuration properties.
     */
    export function capturePhoto(config: capturePhotoConfig): void;
    /**
     * Mobile API. This function returns a normalized URL based on the pui.serverURL configuration setting.
     * @param url The url address of an image file or some other resource.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=7602181
    */
    export function normalizeURL(url: string): string;
    /**
     * Mobile API. This function allows you to show a Rich Display File screen outside of the normal Profound UI session flow.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=4850033
     * @param config JavaScript object containing configuration properties.
    */
    export function show(config: showConfig): void;
    /**
     * Mobile API. This API uploads the contents of a signature pad widget to a file in the IFS on your server running IBM i.
     * http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=9601058
     * @param config JavaScript object containing configuration properties.
    */
    export function uploadSignature(config: uploadSignatureConfig): void;
    /**
     * This API submits a specified response to the server.
     * More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=31261068 
     * @param postData A post data object parameter, no parameters, or a property name parameter with an optional value parameter.
     */
    export function submit( postData: Object | string): void 
}

interface grid {
    collapse(buttonImage?: string | DomElement): void;
    clearState(state?: string): void;
    clearHighlighting(): void;
    expand(buttonImage?: string | DomElement): void;
    exportCSV(fileName?: string): void;
    exportXLSX(fileName?: string): void;
    find(columnIndex: number, text: string, next?: boolean): void;
    getAllDataValues(includeFilter: boolean): any[];
    /**
     * @param row The row number to retrieve value from. Row numbers start with 1.
     * @param col The column number to retrieve value from. Column numbers start with 0. 
    */
    getCellValue(row: number, col: number): string;
    /**
     * @param row The row number to retrieve value from. Row numbers start with 1.
     * @param fieldName The field name as defined in the subfile record format. 
    */
    getDataValue(row: number, fieldName: string): string | null;
    /**
     * @param column The column number to retrieve the filter from. Column numbers start with 0.
    */
    getFilter(column: number): string | null;
    getRecordCount(): number;
    getSelectedCount(): number;
    getSelectedRows(): number[];
    hide(): void;
    /**
     * @param column The column number to hide. Column numbers start with 0.
    */
    hideColumn(column: number): boolean;
    /**
     * @param row The row number to check if is filtered. Row numbers start with 1.
    */
    isRowFilteredOut(row: number): boolean;
    /**
     * @param row The row number to check if is selected. Row numbers start with 1.
    */
    isRowSelected(row: number): boolean;
    /**
     * @param colid  The column ID to check if the column is hidden. Column ID starts with 0 for first, 1 for second, etc.
    */
    isColumnHidden(colid): boolean;
    /**
     * The refresh method refreshes the data in a grid.
     */
    refresh(): void;
    removeAllFilters(): void;
    /**
     * @param column The column number to remove. Column numbers start with 0.
    */
    removeColumn(column: number): void;
    /**
     * @param column The column number to remove the filter from. Column numbers start with 0.
    */
    removeFilter(column: number): void;
    /**
     * The render method re-renders grid.
    */
    render(): void;
    /**
     * @param row The row number to zoom on. Row numbers start with 1.
    */
    rowZoom(row: number): void;
    /**
     * @param row The row number to scroll to. Row numbers start with 1.
    */
    scrollToRow(row): void;
    /**
     * @param relativeRecordNumber The relative record number to set. This must be a Number value 1-9999.
    */
    setCursorRecordNumber(relativeRecordNumber: number): void;
    /**
     * @param row The row number to set the value on. Row numbers start with 1.
     * @param fieldName The field name as defined in the subfile record format.
     * @param value The value to set in the bound field. 
    */
    setDataValue(row: number, fieldName: string, value: string): boolean;
    /**
     * @param column The column number to set the filter on. Column numbers start with 0.
     * @param expression The expression to filter grid data. More info here: http://www.profoundlogic.com/docs/pages/viewpage.action?pageId=24445103
    */
    setFilter(column: number, expression: string): void;
    /**
     * @param rows The new number of visible rows.
     */
    setNumberOfRows(rows: number): boolean;
    setProperty(property: string, value: string): void;
    show(): void;
    /**
     * @param column The column number to show. Column numbers start with 0.
    */
    showColumn(column: number): boolean;
    /**
     * @param column The column number to start filtering on. Column numbers start with 0.
    */
    startFilter(column: number): void;
    /**
     * @param column The column number to start a searching on. Column numbers start with 0.
    */
    startFind(column: number): void;
    toggle(buttonImage?: string | DomElement): void;
}

declare class DomElement extends HTMLElement {
    grid: grid;
     /**
     * @param tab The tab number to activate. The first tab is tab 0.  
     */
    public setTab(tab: number): void;
    /**
     * @param tab The tab number to hide. The first tab is tab 0.  
    */
    public hideTab(tab: number): void;
    /**
     * @param tab The tab number to show. The fist tab is tab 0.
     */
    public showTab(tab: number): void;
    /**
     * @param tab The tab number. The first tab is tab 0. 
     */
    public getTab(tab: number): void;

}

interface ajaxConfig {
    headers?: Object;
    method?: string;
    params?: Object;
    password?: string;
    postData?: string;
    suppressAlert?: boolean;
    url: string;
    user?: string;
    handler?: Function;
}

interface loadJSConfig {
    path: string;
    callback?: Function;
    onerror?: Function;
    test?: Function;
}

interface downloadConfig {
    id: string;
    inline?: boolean;
    contentType?: string;
}

interface downloadURLConfig {
    id: string;
    contentType?: string;
}

interface refreshConfig {
    skin: string;
    url: string;
}

interface runConfig extends signonConfig {
    parameter?: string;
}

interface signonConfig {
    program?: string;
    container?: string;
    workstnid?: string;
    suffixid?: boolean;
    duplicateid?: boolean;
    params?: Object;
    log?: boolean; 
    debug?: boolean; 
    jsonURL?: string;
    mobile?: boolean;
}

interface animateConfig {
    element: string;
    effect?: string;
    property?: string;
    duration?: string;
    type?: string;
    from?: string; 
    to?: string; 
}

interface capturePhotoConfig {
    dir: string;
    fileName: string;
    overwrite: boolean;
    quality: number;
    targetHeight: number;
    targetWidth: number;
    handler: Function;
    success: boolean;
    error: string;
    bytesSent: number;
    responseCode: number;
}

interface showConfig {
    path: string;
    method?: string;
    meta: Object;
    format: string;
    formats: array;
    data: Object;
    handler: Function;
    transition: Object;
    animation: string;
    screen: string;
    overlay: boolean;
    container: string;
}

interface uploadSignatureConfig {
    signaturePadId: string;
    dir: string;
    fileName: string;
    imageType?: string;
    overwrite?: boolean;
    handler: Function;
    success: boolean;
    error: string;
}