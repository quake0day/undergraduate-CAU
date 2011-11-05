#include "StdAfx.h"
#include "BaseList.h"
#include <math.h>

float cc(float a,float b)
{
	return a/b;
}

void CalHistPercent(long hist[256], float hist_radio[256], float &max_percent)
{
	int i,j;
	long max_per = hist[0];	//计算最大值，以扫描的第一点为初值
	long all = 0;	//hist数组中所有的像素个数
	float a=0;


	for( i =0 ; i< 256; i++)
	{
		all = hist[i]+ all;	//循环计算hist数组中所有的像素个数
	}

	for( j= 0; j<256; j++)
	{
		if(all > 0)	//如果像素数目大于0
		{
			hist_radio[j] = cc(hist[j],all) * (float)100;	//计算每个像素点对应所占的百分比，用另外数组保存
		}
		if (max_per < hist[j])
		{
			max_per = hist[j];	//计算最大值
		}

	}

	if( all != 0)	
	{
		a = cc(max_per,all);  //计算最大的百分比
	}		
	else a = 0;

	max_percent = a * (float)100;

}

