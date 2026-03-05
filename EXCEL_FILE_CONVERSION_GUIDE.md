# Excel File Format Guide for DTR Generator

## Issue: .xls Files Not Supported

The DTR Generator system requires Excel files in the newer **.xlsx format**. Older .xls (Excel 97-2003) files are **not compatible** with the system.

### Why .xls Files Don't Work

The .xls files in your excel-files folder cannot be read because:
- They use the old OLE (Object Linking and Embedding) format
- PhpSpreadsheet library requires specific dependencies to read .xls files
- The files may be in a variant of .xls that is not fully supported

### Solution: Convert .xls to .xlsx

You must convert all .xls files to .xlsx format. Here are the easiest methods:

---

## Method 1: Microsoft Excel (Recommended)

1. **Open the .xls file** in Microsoft Excel
2. Click **File** → **Save As**
3. Choose location to save
4. In "Save as type" dropdown, select **Excel Workbook (*.xlsx)**
5. Click **Save**

### Batch Conversion in Excel:
For multiple files, you can use Excel macro or VBA:
```vba
Sub ConvertXlsToXlsx()
    Dim strPath As String
    Dim strFile As String
    Dim wbSource As Workbook
    
    strPath = "C:\xampp\htdocs\DEPEDEvaluationSystem\excel-files\"
    strFile = Dir(strPath & "*.xls")
    
    Do While strFile <> ""
        If Right(strFile, 5) = ".xlsx" Then
            strFile = Dir
            Continue Do
        End If
        
        Set wbSource = Workbooks.Open(strPath & strFile)
        wbSource.SaveAs strPath & Replace(strFile, ".xls", ".xlsx"), FileFormat:=51
        wbSource.Close False
        
        strFile = Dir
    Loop
    
    MsgBox "Conversion Complete!"
End Sub
```

---

## Method 2: LibreOffice Calc (Free)

1. **Download LibreOffice** (free): https://www.libreoffice.org/
2. Open the .xls file in LibreOffice Calc
3. Click **File** → **Save As**
4. In "File type" dropdown, select **Excel 2007-365 (.xlsx)**
5. Click **Save**

---

## Method 3: Google Sheets (Online)

1. Go to **Google Sheets** (sheets.google.com)
2. **Upload the .xls file**: File → Upload
3. Once opened, click **File** → **Download**
4. Select **Microsoft Excel (.xlsx)**
5. Save the downloaded .xlsx file back to the excel-files folder

---

## Method 4: PowerShell Script (Automated)

Save this as `convert_xls_to_xlsx.ps1` in your project folder:

```powershell
# Convert all .xls files to .xlsx in excel-files folder

$excelPath = "C:\xampp\htdocs\DEPEDEvaluationSystem\excel-files"
$excel = New-Object -ComObject Excel.Application
$excel.Visible = $false
$excel.DisplayAlerts = $false

Get-ChildItem -Path $excelPath -Filter "*.xls" | ForEach-Object {
    if ($_.Extension -eq ".xls") {
        Write-Host "Converting: $($_.Name)"
        
        $workbook = $excel.Workbooks.Open($_.FullName)
        $xlsxPath = Join-Path $excelPath ($_.BaseName + ".xlsx")
        
        # 51 = xlOpenXMLWorkbook (xlsx format)
        $workbook.SaveAs($xlsxPath, 51)
        $workbook.Close($false)
        
        Write-Host "  → Saved as: $($_.BaseName).xlsx"
    }
}

$excel.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($excel) | Out-Null

Write-Host "`nConversion complete! All .xls files converted to .xlsx"
```

Run with: `powershell -ExecutionPolicy Bypass .\convert_xls_to_xlsx.ps1`

---

## Files That Need Conversion

Based on the current excel-files folder, these files need conversion:

1. ✗ 7-4-Time-January.xls
2. ✗ CID-January-2026.xls
3. ✗ COS-Jan-1-15.xls
4. ✗ COS-Jan-1-30.xls
5. ✗ COS-Jan-1-30 (1).xls
6. ✗ MOOE-Jan-1-15.xls
7. ✗ MOOE-Jan-1-30.xls
8. ✗ MOOE-Jan-1-30 (1).xls
9. ✗ OSDS-January-2026 (1).xls
10. ✗ Rigor-Brando-January.xls
11. ✗ SGOD-January-2026.xls
12. ✓ Book1.xlsx (Already in correct format)

---

## After Conversion

Once you've converted the files:
1. Refresh the DTR Generator page
2. The files will now show as **valid** with employee counts
3. You can select them for batch processing
4. Generate DTR files normally

---

## Quick Checklist

- [ ] Choose conversion method (Excel, LibreOffice, Google Sheets, or PowerShell)
- [ ] Convert all .xls files to .xlsx
- [ ] Verify converted files open correctly
- [ ] (Optional) Delete old .xls files after confirming .xlsx works
- [ ] Refresh DTR Generator page
- [ ] Process files normally

---

## Support

For questions or issues with file conversion, contact your IT support team or refer to Microsoft Excel documentation.
