import React, { useRef, useState, useEffect } from "react";
import { BASE_URL } from "./config"; // Si está en el directorio padre
import {
  Animated,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
  Dimensions,
} from "react-native";
import {
  PieChart,
  BarChart,
  LineChart,
  ProgressChart,
} from "react-native-chart-kit";
import Icon from "react-native-vector-icons/MaterialIcons";
import * as Animatable from "react-native-animatable";

export default function Dashboard({ navigation }) {
  const [menuOpen, setMenuOpen] = useState(false);
  const [pieChartData, setPieChartData] = useState([]);
  const [lineChartData, setLineChartData] = useState({
    labels: [],
    datasets: [],
  });
  const [barChartData, setBarChartData] = useState({
    labels: [],
    datasets: [],
  });
  const [progressChartData, setProgressChartData] = useState({
    labels: [],
    data: [],
  });
  const [arduinoData, setArduinoData] = useState({
    distancia: 0,
    cajas: 0,
  });

  const sections = [
    { title: "Dashboard", icon: "bar-chart", screen: "Dashboard" },
    { title: "Recepcion", icon: "inbox", screen: "RecepcionScreen" },
    { title: "Proveedores", icon: "local-shipping", screen: "AllProovedores" },
    { title: "Suministros", icon: "inventory", screen: "AllSuministros" },
    { title: "Almacenes", icon: "store", screen: "InterfazWarehouse" },
    { title: "Órdenes", icon: "assignment", screen: "AllOrders" },
    { title: "Reportes", icon: "bar-chart", screen: "AllTransacciones" },
    { title: "Configuración", icon: "settings", screen: "SettingsScreen" },
  ];

  const [arduinoError, setArduinoError] = useState("");
  const { width } = Dimensions.get("window");
  const translateX = useRef(new Animated.Value(-width)).current;

  const toggleMenu = () => {
    Animated.timing(translateX, {
      toValue: menuOpen ? -width * 0.7 : 0,
      duration: 300,
      useNativeDriver: true,
    }).start();
    setMenuOpen(!menuOpen);
  };

  const shortenText = (text, maxLength = 0) =>
    text.length > maxLength ? `${text.substring(5, maxLength)}...` : text;

  // Material por Sub Almacén
  useEffect(() => {
    fetch(
      `${BASE_URL}/PROYECTO4B-1/phpfiles/react/graficasApi.php?type=sub_almacen`
    )
      .then((response) => response.json())
      .then((data) => {
        const chartData = data.map((item) => ({
          name: shortenText(item.sub_almacen, 15), // Abrevia los nombres si superan 15 caracteres
          population: Number(item.total_materiales),
          color: getRandomColor(),
          legendFontColor: "#333",
          legendFontSize: 14,
        }));
        setPieChartData(chartData);
      })
      .catch((error) =>
        console.error("Error al obtener los datos de sub_almacen:", error)
      );
  }, []);

  // Materiales por Tipo
  useEffect(() => {
    fetch(
      `${BASE_URL}/PROYECTO4B-1/phpfiles/react/graficasApi.php?type=materiales_por_tipo`
    )
      .then((response) => response.json())
      .then((data) => {
        const labels = data.map((item) => shortenText(item.tipo_material, 0)); // Abrevia los nombres
        const datasets = [
          {
            data: data.map((item) => Number(item.total_materiales)),
          },
        ];
        setLineChartData({ labels, datasets });
      })
      .catch((error) =>
        console.error(
          "Error al obtener los datos de materiales_por_tipo:",
          error
        )
      );
  }, []);
  const fetchArduinoData = async () => {
    try {
      const response = await fetch(
        `${BASE_URL}/PROYECTO4B-1/phpfiles/react/arduinoApi.php`
      );
      if (!response.ok) {
        throw new Error("Error al obtener datos del Arduino");
      }
      const data = await response.json();
      setArduinoData(data); // Actualiza el estado con los datos del Arduino
      setArduinoError(""); // Limpia cualquier error previo
    } catch (error) {
      console.error("Error al obtener datos del Arduino:", error);
      setArduinoError("No se pudo obtener datos del Arduino.");
    }
  };

  // Llama a fetchArduinoData cada 5 segundos
  useEffect(() => {
    const interval = setInterval(fetchArduinoData, 5000);
    return () => clearInterval(interval); // Limpia el intervalo al desmontar el componente
  }, []);

  // Comparación de Suministros

  useEffect(() => {
    fetch(
      `${BASE_URL}/PROYECTO4B-1/phpfiles/react/graficasApi.php?type=suministros`
    )
      .then((response) => response.json())
      .then((data) => {
        const labels = data.map((item) => shortenText(item.proveedor)); // Abrevia los textos largos
        const datasets = [
          {
            data: data.map((item) => Number(item.total_suministros)),
          },
        ];
        setBarChartData({ labels, datasets });
      })
      .catch((error) =>
        console.error("Error al obtener los datos de suministros:", error)
      );
  }, []);

  // Progreso de Inventario
  useEffect(() => {
    fetch(
      `${BASE_URL}/PROYECTO4B-1/phpfiles/react/graficasApi.php?type=progreso_inventario`
    )
      .then((response) => response.json())
      .then((data) => {
        const labels = data.map((item) => shortenText(item.almacen, 100)); // Abrevia los nombres
        const progressData = data.map((item) =>
          Number(item.porcentaje_ocupacion)
        );
        console.log("Labels:", labels); // Verifica que las etiquetas estén alineadas
        console.log("Data:", progressData); // Verifica que los datos correspondan
        setProgressChartData({ labels, data: progressData });
      })
      .catch((error) =>
        console.error(
          "Error al obtener los datos de progreso_inventario:",
          error
        )
      );
  }, []);
  const getRandomColor = () => {
    const r = Math.floor(Math.random() * 156) + 100;
    const g = Math.floor(Math.random() * 156) + 100;
    const b = Math.floor(Math.random() * 156) + 100;
    return `rgb(${r}, ${g}, ${b})`;
  };

  const chartConfig = {
    backgroundGradientFrom: "#E8F1F5", // Fondo azul claro
    backgroundGradientTo: "#FFFFFF", // Fondo blanco
    backgroundGradientFromOpacity: 1,
    backgroundGradientToOpacity: 1,
    color: (opacity = 1) => `rgba(54, 162, 235, ${opacity})`, // Azul profesional
    strokeWidth: 2, // Ancho de las líneas
    barPercentage: 0.5, // Porcentaje de ancho de las barras
    useShadowColorFromDataset: false, // Desactiva el uso de sombras
    decimalPlaces: 0, // Número de decimales en los valores
    propsForDots: {
      r: "6",
      strokeWidth: "2",
      stroke: "#36A2EB", // Azul oscuro
    },
    propsForBackgroundLines: {
      strokeWidth: 0.5,
      stroke: "rgba(200, 200, 200, 0.5)", // Líneas de fondo gris claro
    },
  };

  return (
    <View style={styles.mainContainer}>
      {!menuOpen && (
        <TouchableOpacity onPress={toggleMenu} style={styles.menuButton}>
          <Icon name="menu" size={30} color="white" />
        </TouchableOpacity>
      )}

      <Animated.View style={[styles.sidebar, { transform: [{ translateX }] }]}>
        <ScrollView contentContainerStyle={styles.sidebarContent}>
          <TouchableOpacity onPress={toggleMenu} style={styles.closeButton}>
            <Icon name="close" size={30} color="#333" />
          </TouchableOpacity>
          <Text style={styles.sidebarTitle}>Menú</Text>

          {/* Opciones del menú */}
          {sections.map((section, index) => (
            <TouchableOpacity
              key={index}
              style={styles.menuItem}
              onPress={() => {
                toggleMenu();
                navigation.navigate(section.screen);
              }}
            >
              <Icon
                name={section.icon}
                size={24}
                color="#007BFF"
                style={styles.menuIcon}
              />
              <Text style={styles.menuText}>{section.title}</Text>
            </TouchableOpacity>
          ))}
        </ScrollView>
      </Animated.View>

      <ScrollView
        style={styles.scrollView}
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        <Animatable.View animation="fadeInUp" style={styles.contentWrapper}>
          <Text style={styles.dashboardTitle}>📊 Dashboard Analítico</Text>

          {/* Gráfica de Pie - Material por Sub Almacén */}
          <Animatable.View
            animation="fadeInLeft"
            delay={300}
            style={[styles.chartContainer, styles.elevatedCard]}
          >
            <View style={styles.chartHeader}>
              <Icon name="pie-chart" size={24} color="#FFA726" />
              <Text style={styles.chartTitle}>
                Distribución por Sub Almacén
              </Text>
            </View>
            {pieChartData.length > 0 ? (
              <PieChart
                data={pieChartData}
                width={Dimensions.get("window").width - 40}
                height={220}
                chartConfig={chartConfig}
                accessor="population"
                backgroundColor="transparent"
                paddingLeft="15"
                absolute
                hasLegend={true}
                style={styles.chartStyle}
              />
            ) : (
              <View style={styles.loadingContainer}>
                <Text style={styles.loadingText}>Cargando datos...</Text>
              </View>
            )}
          </Animatable.View>

          {/* Gráfica de Líneas - Materiales por Tipo */}
          <Animatable.View
            animation="fadeInRight"
            delay={400}
            style={[styles.chartContainer, styles.elevatedCard]}
          >
            <View style={styles.chartHeader}>
              <Icon name="show-chart" size={24} color="#FF6F00" />
              <Text style={styles.chartTitle}>Tendencia de Materiales</Text>
            </View>
            {lineChartData.labels.length > 0 ? (
              <LineChart
                data={lineChartData}
                width={Dimensions.get("window").width - 40}
                height={220}
                chartConfig={{
                  ...chartConfig,
                  color: (opacity = 1) => `rgba(55, 111, 111, ${opacity})`, // Naranja vibrante
                }}
                bezier
                style={styles.chartStyle}
              />
            ) : (
              <View style={styles.loadingContainer}>
                <Text style={styles.loadingText}>Cargando datos...</Text>
              </View>
            )}
          </Animatable.View>

          {/* Gráfica de Barras - Comparación de Suministros */}
          <Animatable.View
            animation="fadeInLeft"
            delay={500}
            style={[styles.chartContainer, styles.elevatedCard]}
          >
            <View style={styles.chartHeader}>
              <Icon name="bar-chart" size={24} color="#FF6F00" />
              <Text style={styles.chartTitle}>Suministros por Proveedor</Text>
            </View>
            {barChartData.labels.length > 0 ? (
              <BarChart
                data={barChartData}
                width={Dimensions.get("window").width - 40}
                height={220}
                chartConfig={{
                  ...chartConfig,
                  color: (opacity = 1) => `rgba(111, 111, 111, ${opacity})`,
                }}
                verticalLabelRotation={0}
                style={styles.chartStyle}
              />
            ) : (
              <View style={styles.loadingContainer}>
                <Text style={styles.loadingText}>Cargando datos...</Text>
              </View>
            )}
          </Animatable.View>

          {/* Gráfica de Progreso - Inventario */}
          <Animatable.View
            animation="fadeInRight"
            delay={600}
            style={[styles.chartContainer, styles.elevatedCard]}
          >
            <View style={styles.chartHeader}>
              <Icon name="donut-large" size={24} color="#FF6F00" />
              <Text style={styles.chartTitle}>Ocupación de Almacenes</Text>
            </View>
            {progressChartData.data.length > 0 ? (
              <ProgressChart
                data={progressChartData}
                width={Dimensions.get("window").width - 100} // Ajusta el ancho de la gráfica
                height={100}
                chartConfig={{
                  ...chartConfig,
                  color: (opacity = 1) => `rgba(54, 162, 235, ${opacity})`, // Azul profesional
                  labelColor: (opacity = 1) => `rgba(0, 0, 0, ${opacity})`, // Color de las etiquetas
                }}
                hideLegend={false} // Muestra la leyenda si es necesario
                style={[styles.chartStyle, styles.progressChartStyle]} // Aplica estilos personalizados
              />
            ) : (
              <View style={styles.loadingContainer}>
                <Text style={styles.loadingText}>Cargando datos...</Text>
              </View>
            )}
          </Animatable.View>

          {/* Datos del Arduino */}
          {/* Datos del Arduino */}
          <Animatable.View
            animation="fadeInUp"
            delay={700}
            style={[styles.arduinoContainer, styles.elevatedCard]}
          >
            <View style={styles.chartHeader}>
              <Icon name="device-thermostat" size={24} color="#F44336" />
              <Text style={styles.chartTitle}>Cajas</Text>
            </View>

            <View style={styles.arduinoDataContainer}>
              <View style={styles.dataItem}>
                <Text style={styles.dataLabel}>Distancia</Text>
                <ProgressChart
                  data={{
                    labels: ["Distancia"], // Etiqueta
                    data: [arduinoData.distancia / 100], // Normaliza la distancia (0 a 1)
                  }}
                  width={Dimensions.get("window").width / 2}
                  height={100}
                  strokeWidth={8}
                  radius={32}
                  chartConfig={{
                    backgroundGradientFrom: "#FFF",
                    backgroundGradientTo: "#FFF",
                    color: (opacity = 1) => `rgba(54, 162, 235, ${opacity})`,
                  }}
                  hideLegend={true}
                />
                <Text style={styles.arduinoText}>
                  {arduinoData.distancia} cm
                </Text>
              </View>

              <View style={styles.dataItem}>
                <Text style={styles.dataLabel}>Cajas Detectadas</Text>
                <Icon
                  name={
                    arduinoData.cajas === 0
                      ? "error-outline"
                      : arduinoData.cajas === 1
                      ? "inbox"
                      : "inventory"
                  }
                  size={50}
                  color={arduinoData.cajas > 0 ? "#4CAF50" : "#F44336"}
                />
                <Text style={styles.arduinoText}>{arduinoData.cajas}</Text>
              </View>
            </View>
            {arduinoError && (
              <View style={styles.errorContainer}>
                <Icon name="error-outline" size={20} color="#F44336" />
                <Text style={styles.errorText}>{arduinoError}</Text>
              </View>
            )}
          </Animatable.View>
        </Animatable.View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  mainContainer: {
    flex: 1,
    backgroundColor: "#F5F7FA", // Fondo gris claro
  },
  scrollView: {
    flex: 1,
    width: "100%",
  },
  scrollContent: {
    paddingBottom: 30,
  },
  contentWrapper: {
    padding: 15,
  },
  menuButton: {
    position: "absolute",
    top: 10,
    left: 10,
    backgroundColor: "#007BFF", // Azul profesional
    padding: 10,
    borderRadius: 5,
    zIndex: 2,
  },

  sidebar: {
    position: "absolute",
    top: 0,
    left: 0,
    width: "75%", // Reduce el ancho del menú
    height: "100%",
    backgroundColor: "#FFFFFF", // Fondo blanco
    zIndex: 1,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 5,
    elevation: 5,
    paddingVertical: 20,
  },
  sidebarContent: {
    flexGrow: 1,
    paddingHorizontal: 20,
  },
  closeButton: {
    alignSelf: "flex-end",
    marginBottom: 20,
  },
  sidebarTitle: {
    fontSize: 22,
    fontWeight: "bold",
    color: "#007BFF", // Azul profesional
    marginBottom: 20,
  },
  menuItem: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 15,
    borderBottomWidth: 1,
    borderBottomColor: "#EEEEEE",
  },
  menuIcon: {
    marginRight: 10,
  },
  menuText: {
    fontSize: 16,
    color: "#333",
    fontWeight: "500",
  },

  elevatedCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 12,
    padding: 16,
    marginBottom: 20,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 6,
    elevation: 5,
  },
  dashboardTitle: {
    fontSize: 26,
    fontWeight: "700",
    color: "#333",
    marginBottom: 25,
    textAlign: "center",
  },
  chartContainer: {
    width: "100%",
    overflow: "hidden",
  },
  chartHeader: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 15,
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderBottomColor: "#EEEEEE",
  },
  chartTitle: {
    fontSize: 18,
    fontWeight: "600",
    color: "#333",
    marginLeft: 10,
  },
  chartStyle: {
    borderRadius: 8,
    marginTop: 10,
  },
  loadingContainer: {
    height: 220,
    justifyContent: "center",
    alignItems: "center",
  },
  loadingText: {
    fontSize: 16,
    color: "#666",
    fontStyle: "italic",
  },
  arduinoContainer: {
    paddingVertical: 20,
  },
  arduinoDataContainer: {
    flexDirection: "row",
    justifyContent: "space-around",
    marginTop: 15,
  },
  dataItem: {
    alignItems: "center",
    padding: 15,
    borderRadius: 10,
    backgroundColor: "#F8F9FA", // Fondo gris claro
    width: "45%",
  },
  arduinoText: {
    fontSize: 24,
    fontWeight: "bold",
    marginVertical: 5,
    color: "#333",
  },
  dataLabel: {
    fontSize: 14,
    color: "#666",
  },
  progressChartStyle: {
    paddingBottom: 50, // Espacio adicional para los labels
    marginHorizontal: 10,
    alignItems: "center",
  },
  labelText: {
    fontSize: 12, // Tamaño de fuente más pequeño para los labels
    color: "#333", // Color oscuro para mejor legibilidad
    textAlign: "center", // Centrar el texto
  },
});
