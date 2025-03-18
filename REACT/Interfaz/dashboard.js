import React from "react";
import { View, Text, StyleSheet, ScrollView, useWindowDimensions, SafeAreaView } from "react-native";
import { PieChart } from "react-native-chart-kit";

export default function Dashboard() {
  const { width } = useWindowDimensions();

  const data = [
    { name: "Laptop A", population: 50, color: "#FF6384", legendFontColor: "#111", legendFontSize: 14 },
    { name: "Laptop B", population: 30, color: "#36A2EB", legendFontColor: "#111", legendFontSize: 14 },
    { name: "Laptop C", population: 70, color: "#FFCE56", legendFontColor: "#111", legendFontSize: 14 },
    { name: "Laptop D", population: 20, color: "#4BC0C0", legendFontColor: "#111", legendFontSize: 14 },
    { name: "Laptop E", population: 90, color: "#9966FF", legendFontColor: "#111", legendFontSize: 14 },
  ];

  return (
    <SafeAreaView style={styles.safeArea}>
      <ScrollView style={styles.container} showsVerticalScrollIndicator={false}>
        <Text style={styles.title}>Dashboard</Text>
        <Text style={styles.subtitle}>Distribución de Material</Text>

        <PieChart
          data={data}
          width={width - 32} // Se ajusta al tamaño de la pantalla
          height={250}
          chartConfig={{
            backgroundGradientFrom: "#1e3c72",
            backgroundGradientTo: "#2a5298",
            decimalPlaces: 0,
            color: (opacity = 1) => `rgba(255, 255, 255, ${opacity})`,
            labelColor: (opacity = 1) => `rgba(255, 111, 111, ${opacity})`,
          }}
          accessor="population"
          backgroundColor="transparent"
          paddingLeft="15"
          absolute
        />
        
      </ScrollView>
    </SafeAreaView>
  );
}



const styles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: "#FFFFFF",
  },
  container: {
    flex: 1,
    padding: 16,
  },
  title: {
    fontSize: 26,
    fontWeight: "bold",
    color: "#1111111",
    textAlign: "center",
    marginBottom: 5,
  },
  subtitle: {
    fontSize: 18,
    fontWeight: "bold",
    color: "#111111",
    textAlign: "center",
    marginBottom: 12,
  },
});
