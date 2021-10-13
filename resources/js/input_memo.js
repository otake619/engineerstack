$(function() {
    let array;
    $("#category").keyup(function() {
        const separator = ",";
        let inputText = $(this).val();
        let textToArray = separateText(separator, inputText);
        array = checkElement(textToArray);
        let dispText = arrayToText(textToArray);
        let tags = pushTag(array);
        $("#disp_category").html(tags);

        function getCategoriesArr(array) {
            return array;
        }
    });

    $("#title").keyup(function() {
        const id = "#count_title";
        let limit = 100;
        let countTitle = $(this).val().length;
        countText(id, limit, countTitle);
    });

    $("#memo").keyup(function() {
        const id = "#count_memo";
        let limit = 2000;
        let countMemo = $(this).val().length;
        countText(id, limit, countMemo);
    });

    $("#file").change(function() {
        //TODO: error時の対応も記述
        let fileData = this.files[0];
        setFileName("#file_name", fileData);
        setFileSize("#file_size", fileData);
        checkSize(getFileSize(fileData));
        setPreview("#preview", getUrl(fileData));
    });

    $("#post_memo").on('click', function () {
        let title = $("#title").val();

        $.ajaxSetup( {
            headers: {
                'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
            }
        });

        $.ajax({
            type: "post",
            url: "/memos/store",
            datatype: "json",
            data: {
                "categories": array,
                "title": title
            },

            success: function(data) {
                console.log("通信成功");
                $("#category").val(array);
            },
            error: function(data) {
                console.log("通信失敗");
            }
        });

        return false;
    });
    
});

//カテゴリの関数
function separateText(separator, text) {
    let splitText = text.split(separator);
    return splitText;
}

function arrayToText(array) {
    let text = array.join(" ");
    return text;
}

function countCategory(categoryArray) {
    let length = categoryArray.length;
    let remain = 5 - length;
    const id = "#count_category";

    if(remain > 0) {
        const text = "残り" + remain + "個入力可能";
        changeText(id, text);
    } else if(remain === 0){
        const text = "入力できる最大数です。";
        changeText(id, text);
    } else {
        const isNormal = false;
        const text = "最大数を超えています!";
        changeText(id, text);
        changeClass(id, isNormal);
    }
}

function checkElement(array) {
    const id = "#count_category";

    for(let i=0;i<array.length;i++) {
        if(array[i] === '' || array[i].length === 0) {
            array.splice(i, 1);
        } else if(array[i].length > 30) {
            const isNormal = false;
            const text = "カテゴリが30文字を超えています!";
            changeText(id, text);
            changeClass(id, isNormal);
            return;
        } else {
            const isNormal = true;
            changeClass(id, isNormal);
        }
    }
    countCategory(array);
    return array;
}

function createElement(tag, type, text) {
    let element = $(`<${tag}>`, {class:type, text: text});
    return element;
}

function pushTag(array) {
    let tags = [];
    //カテゴリの要素数が5より上の場合は処理を中断
    if(array == undefined) {
        return;
    } else if(array.length > 5) {
        return;
    }
    for(let i=0; i<array.length; i++) {
        let element = createElement("i", "fas fa-tape tag is-primary mr-1 mb-1", array[i]);
        tags.push(element);
    }
    return tags;
}
//ここまで

//タイトルとメモの関数
function countText(id, limit, length) {
    let remain = limit -length;
    if(remain > 0) {
        const isNormal = true;
        const text = "残り" + remain + "文字入力可能";
        changeText(id, text);
        changeClass(id, isNormal);
    } else if(remain === 0) {
        const isNormal = true;
        const text = "入力できる最大文字数です。";
        changeText(id, text);
        changeClass(id, isNormal);
    } else {
        const isNormal = false;
        const text = "最大文字数を超えています！";
        changeText(id, text);
        changeClass(id, isNormal);
    }
}
//ここまで

//共通の関数
function changeClass(id, isNormal) {
    let normalStatus = "has-text-primary";
    let abnormalStatus = "has-text-danger";
    
    if(isNormal) {
        $(id).attr("class", normalStatus);
    } else {
        $(id).attr("class", abnormalStatus);
    }
}

function changeText(id, text) {
    $(id).text(text);
}
//ここまで

//ファイルの関数
function getFileSize(fileInfo) {
    let size = fileInfo.size/1024/1024;
    size = Math.ceil(size * 1000)/1000;
    let fileSize = size + "MB";
    return fileSize;
}

function getFileName(fileInfo) {
    let fileName = fileInfo.name;
    return fileName;
}

function setFileSize(id, fileInfo) {
    let fileSize = getFileSize(fileInfo);
    $(id).text(fileSize);
}

function setFileName(id, fileInfo) {
    let fileName = getFileName(fileInfo);
    $(id).text(fileName);
}

function checkSize(fileSize) {
    let rowFileSize = fileSize.slice(0, -2);
    rowFileSize = Number(rowFileSize);
    
    if(rowFileSize > 5.0) {
        const isDisabled = true;
        const id = "#post_memo";
        const isNormal = false;
        switchDisabled(id, isDisabled);
        changeText("#file_size", "5.0MBを超えています!");
        changeClass("#file_size", isNormal);
    } else {
        const isDisabled = false;
        const id = "#post_memo";
        const isNormal = true;
        switchDisabled(id, isDisabled);
        changeClass("#file_size", isNormal);
    }
}

function setPreview(id, url) {
    $(id).attr("src", url);
}

function getUrl(fileData) {
    let blobUrl = window.URL.createObjectURL(fileData);
    return blobUrl;
}
//ここまで
//ボタンの関数
function switchDisabled(id, isDisabled) {
    if(isDisabled) {
        $(id).attr("disabled", true);
    } else {
        $(id).attr("disabled", false);
    }
}
//ここまで


