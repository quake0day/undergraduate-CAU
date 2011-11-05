#if !defined(AFX_ZFT_H__A564F3D2_CCE4_45CB_A030_8888F35AA0BE__INCLUDED_)
#define AFX_ZFT_H__A564F3D2_CCE4_45CB_A030_8888F35AA0BE__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// zft.h : header file
//

/////////////////////////////////////////////////////////////////////////////
// Czft dialog

class Czft : public CDialog
{
// Construction
public:
	Czft(CWnd* pParent = NULL);   // standard constructor

// Dialog Data
	//{{AFX_DATA(Czft)
	enum { IDD = IDD_zft_DIALOG1 };
		// NOTE: the ClassWizard will add data members here
	//}}AFX_DATA
	CWnd *m_pParent;

	BYTE *m_pImage_in;
	BYTE *m_pImage_out;

	int m_nxsize;
	int m_nysize;
	int m_shift_val,m_left,m_top,m_right,m_bottom;
	bool m_bAxisText;
	bool m_create;
	int m_max_value_x;
	int m_max_value_y;
	int m_text_delta_x;
	int m_text_delta_y;
	float m_small_x;
	float m_large_x;
	float m_small_y;
	float m_large_y;
	long m_histSmooth[256];
	float m_disp_array[256];
	float m_max_percent;
	float m_resize_x;
	float m_resize_y;
	float m_zoom_x;
	float m_zoom_y;
	float m_inzoom_y;
	float m_inzoom_x;


	void DispHistogram();

	short DrawHistArea( CDC* pDC);
	void DrawAxisText( CDC* pDC);
	//void DrawAxisXLine( CDC* pDC);
	void DrawAxisYLine( CDC* pDC);
	void DrawSubLine( CDC* pDC);
	void DrawGrayValueMono( CDC* pDC);
	void DrawAxisLine(CDC* pDC);
	void DrawAxisXline(CDC* pDC);

	BYTE *m_pImage_hist;
	long m_hist[256];
	long m_pHist_in[256];
	long m_pHist_out[256];

	BOOL m_bflge;


// Overrides
	// ClassWizard generated virtual function overrides
	//{{AFX_VIRTUAL(Czft)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support
	//}}AFX_VIRTUAL

// Implementation
protected:

	// Generated message map functions
	//{{AFX_MSG(Czft)
	virtual BOOL OnInitDialog();
	afx_msg void OnPaint();
	afx_msg void OnHistOriginal();
	afx_msg void DrawAxisYLine();
	afx_msg void DrawSubLine();
	afx_msg void DrawAxisXLine();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ will insert additional declarations immediately before the previous line.

#endif // !defined(AFX_ZFT_H__A564F3D2_CCE4_45CB_A030_8888F35AA0BE__INCLUDED_)
