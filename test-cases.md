Scenario: Successful Report Generation
	Given User is in terminal
	When User triggers report generation
	Then report is displayed for the available data

Scenario: Unsuccessful Report Generation - missing data: database in unavailable 
	Given User is in terminal
	When User triggers report generation
  And there is no report data available
	Then report displays error message

Scenario: Unsuccessful Report Generation - missing data: table is empty 
	Given User is in terminal
	When User triggers report generation
  And there is no report data available
	Then report displays error message

Scenario: Unsuccessful Report Generation - missing data: corrupted data
	Given User is in terminal
	When User triggers report generation
  And there is no report data available
	Then report displays error message

Scenario: Unsuccessful Report Generation - missing data on Profile names
	Given User is in terminal
	When User triggers report generation
  And there is no data on profile names available
	Then report displays error message
  
